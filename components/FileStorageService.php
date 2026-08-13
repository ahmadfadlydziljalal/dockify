<?php

namespace app\components;

use app\models\SupportingDocument;
use Throwable;
use Yii;
use yii\base\Component;
use yii\console\Response;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\web\RangeNotSatisfiableHttpException;

/**
 * Class FileStorageService
 *
 *
 * This class provides file storage services,
 * handling file uploads differently based on the application environment (development or production).
 *
 * For save the information about the uploaded file, please see table `it.supporting_document`.
 * There is FK `supporting_document.request_form_id` references `request_form.id` for linking the uploaded files to request forms.
 *
 * @package app\components
 */
class FileStorageService extends Component {


    const DEVELOPMENT_ENVIRONMENT = 'development';
    const PRODUCTION_ENVIRONMENT = 'production';

    public SupportingDocument|null $supportingDocument = null;
    protected ?string $columnId = null;

    /**
     * Upload file method.
     * Decides which upload method to use based on the environment.
     * @param string|null $columnId Optional foreign key column name to link the uploaded file.
     * @return array Returns array with two elements:
     * - on success: ['success', <file data array>]
     * - on error:   ['error', <error message>]
     */
    public function upload(string $columnId = null): array {
        $this->columnId = $columnId;
        return YII_ENV_DEV ? $this->uploadDevelopment() : $this->uploadProduction();
    }

    /**
     * Download file method.
     * Decides which download method to use based on the environment.
     * @throws RangeNotSatisfiableHttpException
     */
    public function download(bool $inline = false): \yii\web\Response|Response {
        return YII_ENV_DEV ? $this->downloadDevelopment($inline) : $this->downloadProduction($inline);
    }

    /**
     * Delete file method.
     * Decides which delete method to use based on the environment.
     * @return bool|int
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function drop(): bool|int {
        return YII_ENV_DEV ? $this->dropDevelopment() : $this->dropProduction();
    }

    /**
     * Upload file to local storage (development environment).
     * Returns array with two elements:
     * - on success: ['success', <file data array>]
     * - on error:   ['error', <error message>]
     */
    protected function uploadDevelopment(): array {

        if (empty($_FILES['fileData'])) {
            return ['error', 'No files found for upload'];
        }

        // save file to folder 'uploads/'
        $uploadDir = Yii::getAlias('@app/uploads/');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // build suffix like 'request_form/123/' if columnId provided
        $suffix = $this->getEntityPathSuffix();

        // ensure entity-specific folder exists when suffix provided
        if ($suffix !== '') {
            $fullDir = $uploadDir . $suffix;
            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0755, true);
            }
        } else {
            $fullDir = $uploadDir;
        }

        // base file path
        $filePath = $fullDir . basename($_FILES['fileData']['name']);

        // save file to local storage
        if (move_uploaded_file($_FILES['fileData']['tmp_name'], $filePath)) {

            // delegate record creation to helper
            return $this->createSupportingDocumentRecord($_FILES['fileData']['name'], $filePath);

        } else {
            Yii::$app->response->statusCode = 500;
            return [
                'error', 'Error While uploading image, contact the system administrator' . Json::encode($_FILES['fileData']["error"])
            ];

        }

    }

    /**
     * Download file from local storage (development environment).
     * @param bool $inline
     * @return Response|\yii\web\Response
     */
    private function downloadDevelopment(bool $inline): \yii\web\Response|Response {
        return Yii::$app->response->sendFile(
            $this->supportingDocument->file_path,
            $this->supportingDocument->file_name, [
                'inline' => $inline
            ]
        );
    }

    /**
     * Delete file from local storage (development environment).
     * @return bool|int
     * @throws Throwable
     * @throws StaleObjectException
     */
    private function dropDevelopment(): bool|int {
        // delete file from local storage, and then delete record from database
        if (file_exists($this->supportingDocument->file_path)) {
            unlink($this->supportingDocument->file_path);
        }
        return $this->supportingDocument->delete();

    }

    /**
     * Upload file to S3 (production environment).
     * Returns array with two elements:
     * - on success: ['success', <file data array>]
     * - on error:   ['error', <error message>]
     */
    protected function uploadProduction(): array {

        if (empty($_FILES['fileData'])) {
            return ['error', 'No files found for upload'];
        }

        // gunakan component `s3Client` di `web.php` configuration
        $s3Client = Yii::$app->s3Client;
        $bucket = $s3Client->bucket;

        // build key using the same suffix helper
        $suffix = $this->getEntityPathSuffix();
        $key = 'it-helpdesk/' . $suffix . basename($_FILES['fileData']['name']);

        try {
            // upload file to S3
            $s3Client->getClient()->putObject([
                'Bucket'     => $bucket,
                'Key'        => $key,
                'SourceFile' => $_FILES['fileData']['tmp_name'],
                'ACL'        => 'public-read',
            ]);

            // delegate record creation to helper (store key as file_path)
            return $this->createSupportingDocumentRecord($_FILES['fileData']['name'], $key);

        } catch (Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'error', 'Error While uploading image to S3, contact the system administrator: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Download file from S3
     * @param $inline
     * @return Response|\yii\web\Response
     * @throws RangeNotSatisfiableHttpException
     */
    private function downloadProduction($inline): \yii\web\Response|Response {
        // gunakan component `s3Client` di `web.php` configuration
        $s3Client = Yii::$app->s3Client;

        $result = $s3Client->getClient()->getObject([
            'Bucket' => $s3Client->bucket,
            'Key'    => $this->supportingDocument->file_path,
        ]);

        if (!$inline) {
            return Yii::$app->response->sendContentAsFile(
                $result['Body'],
                $this->supportingDocument->file_name
            );
        }

        $mimeType = match ($this->supportingDocument->file_extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'txt' => 'text/plain',
            default => 'application/' . $this->supportingDocument->file_extension,
        };

        return Yii::$app->response->sendContentAsFile(
            $result['Body'],
            $this->supportingDocument->file_name, [
                'inline'   => $inline,
                'mimeType' => $mimeType
            ]
        );
    }

    /**
     * Delete file from S3 (production environment).
     * @return bool|int
     * @throws Throwable
     * @throws StaleObjectException
     */
    private function dropProduction(): bool|int {
        // gunakan component `s3Client` di `web.php` configuration
        $s3Client = Yii::$app->s3Client;

        // delete file from S3, and then delete record from database
        $s3Client->getClient()->deleteObject([
            'Bucket' => $s3Client->bucket,
            'Key'    => $this->supportingDocument->file_path,
        ]);
        return $this->supportingDocument->delete();
    }

    /**
     * Build entity-specific path/key suffix from columnId and POST id.
     * Returns empty string when no columnId provided.
     * Example return: 'request_form/27/'
     */
    private function getEntityPathSuffix(): string {
        if ($this->columnId !== null) {
            $entityDir = str_replace('_id', '', $this->columnId) . '/';
            $entityId = Yii::$app->request->post('id') . '/';
            return $entityDir . $entityId;
        }
        return '';
    }

    /**
     * Create and save SupportingDocument record.
     * Returns the same success/error array shapes used previously.
     */
    private function createSupportingDocumentRecord(string $fileName, string $filePath): array {
        $this->supportingDocument = new SupportingDocument([
            'file_name'      => $fileName,
            'file_path'      => $filePath,
            'file_extension' => strtolower(pathinfo($filePath, PATHINFO_EXTENSION)),
            'version'        => 1,
            'environment'    => YII_ENV_DEV ? self::DEVELOPMENT_ENVIRONMENT : self::PRODUCTION_ENVIRONMENT,
            'is_active'      => 1,
        ]);

        // assign foreign key if columnId is provided
        if ($this->columnId !== null) {
            $this->supportingDocument->{$this->columnId} = Yii::$app->request->post('id');
        }

        // save to database
        if ($this->supportingDocument->save()) {
            return [
                'success',
                $_FILES['fileData']
            ];
        }

        // error while saving to database
        Yii::$app->response->statusCode = 500;
        return [
            'error',
            'Error While saving file record to database, contact the system administrator' . Json::encode($this->supportingDocument->getErrors())
        ];
    }

}
