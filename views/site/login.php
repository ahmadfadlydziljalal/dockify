<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

$this->title = 'Login to your account';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Log in to access your ' . Yii::$app->name . ' application account.';
$this->params['meta_keywords'] = Yii::$app->name . ', login, sign in, authentication';

$htmlIcon = <<<HTML
{label}<div class="input-group">{input}<span class="input-group-text" aria-hidden="true">%s</span></div>{error}{hint}
HTML;
$passwordIconTemplate = <<<HTML
{label}<div class="input-group">{input}<span id="toggle-login-password" class="input-group-text" role="button" tabindex="0" aria-label="Show password" title="Show password" style="cursor:pointer;">%s</span></div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold small'];

$this->registerJs(<<<JS
(() => {
    const toggle = document.getElementById('toggle-login-password');
    const passwordInput = document.getElementById('loginform-password');

    if (!toggle || !passwordInput) {
        return;
    }

    const setState = (isVisible) => {
        passwordInput.type = isVisible ? 'text' : 'password';
        toggle.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
        toggle.setAttribute('title', isVisible ? 'Hide password' : 'Show password');
    };

    const togglePassword = () => {
        setState(passwordInput.type === 'password');
    };

    toggle.addEventListener('click', togglePassword);
    toggle.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            togglePassword();
        }
    });
})();
JS, View::POS_READY);
?>
<div class="site-login d-flex align-items-center justify-content-center py-5 px-4  px-md-0 px-lg-0 px-xl-0 px-xxl-0"
     style="flex:1">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">

            <!-- Brand panel -->
            <div class="col-md-5 d-none d-md-flex login-brand-panel text-white">
                <div class="d-flex flex-column justify-content-between p-4 p-lg-5 w-100">
                    <div>
                        <?php // Yii::$app->params['appIcon'] . ' <span class="ms-2">' . Yii::$app->name . '</span>'?>
                        <?= Html::a(
                            Html::img(
                            Yii::getAlias('@web/images/yii3_full_white_for_dark.svg'),
                            [
                                'alt' => 'Yii Framework',
                                'class' => 'mb-4',
                                'height' => 40,
                            ])
                            ,
                            Yii::$app->homeUrl,
                            ['class' => ' mb-4'],
                        ) ?>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-3 login-brand-title">
                            Welcome Back ...!
                        </h2>
                        <p class="opacity-75 mb-0 login-brand-text">
                            Log in to access Your Application and manage your account.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form panel -->
            <div class="col-md-7">
                <div class="p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <!-- Mobile-only logo -->
                        <div class="d-md-none mb-3">
                            <?= Html::img(
                                Yii::getAlias('@web/images/yii3_full_black_for_light.svg'),
                                [
                                    'alt'    => 'Yii Framework',
                                    'class'  => 'login-mobile-logo',
                                    'height' => 36,
                                ],
                            ) ?>
                        </div>
                        <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
                        <p class="text-body-secondary small">Enter your credentials to continue</p>
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <div class="mb-3">
                        <?= $form->field($model, 'username', [
                            'options'      => ['class' => 'mb-0'],
                            'template'     => sprintf($htmlIcon, '&#128100;'),
                            'inputOptions' => [
                                'class'       => 'form-control',
                                'placeholder' => 'Username',
                                'autofocus'   => true,
                            ],
                        ])->textInput()->label('Your Username', $labelOptions) ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'password', [
                            'options'      => ['class' => 'mb-0'],
                            'template'     => sprintf($passwordIconTemplate, '&#128274;'),
                            'inputOptions' => [
                                'class'       => 'form-control',
                                'placeholder' => 'Password',
                            ],
                        ])->passwordInput()->label('Your Password', $labelOptions) ?>
                    </div>

                    <div class="mb-4">
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    </div>

                    <div class="d-grid">
                        <?= Html::submitButton(
                            'Login',
                            [
                                'class' => 'btn login-btn btn-lg rounded-3 text-white',
                                'name'  => 'login-button',
                            ],
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="text-body-secondary text-center mt-3 small">
                        You may log in with your <strong>SIHRD Account</strong>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
