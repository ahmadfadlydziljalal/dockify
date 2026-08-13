<?php

namespace app\components;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Exception;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class S3ClientComponent extends Component
{
    public ?string $region = null;
    public ?string $version = 'latest';
    public ?string $endpoint = null;
    public ?array $credentials = [];
    public ?string $bucket = null;
    public ?string $baseUrl = null;

    private ?S3Client $_s3Client = null;

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->region)) {
            throw new InvalidConfigException('S3 region must be specified');
        }
        if (empty($this->bucket)) {
            throw new InvalidConfigException('S3 bucket must be specified');
        }
        if (empty($this->baseUrl)) {
            throw new InvalidConfigException('S3 base URL must be specified');
        }

        $config = [
            'region' => $this->region,
            'version' => $this->version,
            'credentials' => $this->credentials,
        ];

        if (!empty($this->endpoint)) {
            $config['endpoint'] = $this->endpoint;
        }

        // initialization S3Client
        $this->_s3Client = new S3Client($config);
    }

    /**
     * Get S3Client instance
     * @return S3Client
     */
    public function getClient(): S3Client
    {
        return $this->_s3Client;
    }

    /**
     * Check if an object exists in S3
     * @param string $key
     * @return bool
     */
    public function objectExists(string $key): bool
    {
        try {
            $this->getClient()->headObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return true;
        } catch (S3Exception $e) {
            return false;
        }
    }

    /**
     * Upload a file to S3
     * @param string $key
     * @param string $sourceFile
     * @param string $contentType
     * @param string $acl
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function uploadFile(string $key, string $sourceFile, string $contentType = 'application/octet-stream', string $acl = 'public-read'): bool
    {
        try {
            $this->getClient()->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $sourceFile,
                'ContentType' => $contentType,
                'ACL' => $acl,
            ]);
            return true;
        } catch (Exception $e) {
            Yii::error('Failed to upload file to S3: ' . $e->getMessage(),'S3ClientComponent');
            throw new ServerErrorHttpException('File upload failed.');
        }
    }

    /**
     * Download a file from S3
     * @param string $key
     * @param string $saveAs Local path where to save the file
     * @return bool
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function downloadFile(string $key, string $saveAs): bool
    {
        try {
            $this->getClient()->getObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SaveAs' => $saveAs,
            ]);
            return true;
        } catch (S3Exception $e) {
            if ($e->getAwsErrorCode() === 'NoSuchKey') {
                throw new NotFoundHttpException('File not found in S3: ' . $key);
            }
            Yii::error('Failed to download file from S3: ' . $e->getMessage());
            throw new ServerErrorHttpException('File download failed.');
        }
    }

    /**
     * Get file content from S3
     * @param string $key
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function getFileContent(string $key): string
    {
        try {
            $result = $this->getClient()->getObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return $result['Body']->getContents();
        } catch (S3Exception $e) {
            if ($e->getAwsErrorCode() === 'NoSuchKey') {
                throw new NotFoundHttpException('File not found in S3: ' . $key);
            }
            Yii::error('Failed to get file content from S3: ' . $e->getMessage());
            throw new ServerErrorHttpException('Failed to retrieve file content.');
        }
    }

    /**
     * Delete a file from S3
     * @param string $key
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function deleteFile(string $key): bool
    {
        try {
            $this->getClient()->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return true;
        } catch (Exception $e) {
            Yii::error('Failed to delete file from S3: ' . $e->getMessage());
            throw new ServerErrorHttpException('File deletion failed.');
        }
    }

    /**
     * Get public URL for S3 object
     * @param string $key
     * @return string
     */
    public function getPublicUrl(string $key): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($key, '/');
    }

    /**
     * Generate presigned URL for temporary access
     * @param string $key
     * @param string $expires
     * @return string
     */
    public function getPresignedUrl(string $key, string $expires = '+1 hour'): string
    {
        $command = $this->getClient()->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        return (string)$this->getClient()->createPresignedRequest($command, $expires)->getUri();
    }
}
