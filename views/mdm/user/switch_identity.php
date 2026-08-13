<?php

use app\models\User;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use app\models\forms\UserSwitchIdentityForm;

/** @var $this View */
/** @var $model UserSwitchIdentityForm */
/** @see \app\controllers\UserController::actionSwitchIdentity */


$this->title = 'Switch Identity';

$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['/admin/user/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-form">

    <h1 class="mb-3"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([]) ?>

    <?= $form->field($model, 'userId')->label('User ID')->widget(Select2::class, [
        'data' => User::find()->map('id', 'nama_karyawan', 'nama_karyawan')
    ]) ?>

    <?= Html::submitButton('Login As', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>
</div>
