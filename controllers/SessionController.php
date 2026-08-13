<?php

namespace app\controllers;

use app\models\search\SessionSearch;
use app\models\Session;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SessionController implements the CRUD actions for Session model.
 */
class SessionController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors(): array {
        return [
            //[
            // 'class' => 'yii\filters\AjaxFilter',
            // 'except' => ['index'],
            // 'only' => ['create','update','view','delete',],
            //],
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete'     => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Session models.
     * @return Response|string
     */
    public function actionIndex(): Response|string {
        $searchModel = new SessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Session model.
     * @param string $id
     * @return array | Response | string
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): array|Response|string {
        if ($this->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'   => "Session #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'  => Html::button('Close', ['class' => 'btn btn-secondary me-auto', 'data-bs-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Delete an existing Session model.
     * For ajax request will return a JSON object
     * @param string $id
     * @return array | Response
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(string $id): array|Response {
        $this->findModel($id)->delete();
        if ($this->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose'  => true,
                'forceReload' => '#crud-datatable-pjax'
            ];
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Session model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Session the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $id): Session {

        if (($model = Session::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
