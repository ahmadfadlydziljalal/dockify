<?php

/* @var $this yii\web\View */
/* @var $model app\models\Session */
/* @see app\controllers\SessionController::actionUpdate() */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Update Session: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Session'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="session-update  d-flex flex-column gap-3">

    <?php if (!Yii::$app->request->isAjax){ ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
