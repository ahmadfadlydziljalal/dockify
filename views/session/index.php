<?php

use dzil\crud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @see app\controllers\SessionController::actionIndex() */

$this->title = Yii::t('app', 'Session');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
    <div class="session-index">
        <div id="ajaxCrudDatatable" class="d-flex flex-column gap-2">

            <div class="d-flex justify-content-between flex-wrap gap-4 gap-md-2">
                <h1 class="my-0"><?= Html::encode($this->title) ?></h1>

            </div>

            <?php try {
                echo GridView::widget([
                    'id'           => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'pjax'         => true,
                    'columns'      => require(__DIR__ . '/_columns.php'),
                    'panel'        => false,
                    'bordered'     => true,
                    'striped'      => true,
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
        </div>
    </div>

<?php Modal::begin([
    "id"            => "ajaxCrudModal",
    "size"          => "modal-xl modal-fullscreen-xl-down",
    "footer"        => "",// always need it for jquery plugin
    "options"       => [
        "tabindex" => false // important for Select2 to work properly
    ],
    /*"dialogOptions" => [
        "class" => "modal-dialog-scrollable"
    ],*/
    "clientOptions" => [
        "backdrop" => "static",
        "keyboard" => false
    ],
    "scrollable"    => true,
]) ?>
<?php Modal::end(); ?>
<?php $this->registerJs(<<<JS
    jQuery(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
JS
) ?>
