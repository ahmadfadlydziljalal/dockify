<?php
/* @var $this yii\web\View */
/* @var $model app\models\Session */
/* @see app\controllers\SessionController::actionCreate() */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Create New Session');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Session'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="session-create d-flex flex-column gap-3">

    <?php if (!Yii::$app->request->isAjax){ ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
