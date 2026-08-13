<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */
/** @var bool $contactSubmitted */
/** @see \app\controllers\SiteController::actionContact() */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Contact us';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Get in touch with us. Send us a message using the contact form.';
$this->params['meta_keywords'] = 'yii, yii2, contact, support, feedback';
$contactSubmitted = $contactSubmitted ?? false;
$htmlIcon = <<<HTML
{label}<div class="input-group"><span class="input-group-text" aria-hidden="true">%s</span>{input}</div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold small'];
?>
<?php if ($contactSubmitted || Yii::$app->session->hasFlash('success')): ?>

<div class="site-contact-success d-flex align-items-center justify-content-center text-center">
    <div class="site-contact-success-content mx-auto">
        <h1 class="display-6 fw-semibold mb-3">Message sent</h1>
        <p class="text-body-secondary mb-4">
            Thank you for contacting us. We will respond to you as soon as possible.
        </p>

        <?php if (YII_DEBUG && Yii::$app->mailer->useFileTransport): ?>
            <p class="text-body-tertiary small mb-4">
                Development mode: email saved under
                <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>
            </p>
        <?php endif; ?>

        <?= Html::a(
            'Send another message',
            ['contact'],
            ['class' => 'btn btn-outline-primary btn-lg'],
        ) ?>
    </div>
</div>

<?php else: ?>

<div class="site-contact">
    <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
    <p class="text-body-secondary small">Fill out the form below and we will get back to you</p>
    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <div class="row">
        <div class="col-sm-6 mb-3">
            <?= $form->field($model, 'name', [
                'options' => ['class' => 'mb-0'],
                'template' => sprintf($htmlIcon, '&#128100;'),
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'autofocus' => true,
                ],
            ])->label('Your Name', $labelOptions) ?>
        </div>

        <div class="col-sm-6 mb-3">
            <?= $form->field($model, 'email', [
                'options' => ['class' => 'mb-0'],
                'template' => sprintf($htmlIcon, '&#9993;'),
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'email@example.com',
                ],
            ])->label('Your Email', $labelOptions) ?>
        </div>
    </div>
    <div class="mb-3">
        <?= $form->field($model, 'subject', [
            'options' => ['class' => 'mb-0'],
            'template' => sprintf($htmlIcon, '&#128172;'),
            'inputOptions' => [
                'class' => 'form-control',
                'placeholder' => 'Subject',
            ],
        ])->label('Subject', $labelOptions) ?>
    </div>
    <div class="mb-3">
        <?= $form->field($model, 'body', [
            'options' => ['class' => 'mb-0'],
            'template' => '{label}{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control',
                'placeholder' => 'Your message...',
            ],
        ])->textarea()->label('Message', $labelOptions) ?>
    </div>
    <div class="d-flex justify-content-end align-items-center gap-3 flex-wrap">
        <?= $form->field($model, 'verifyCode', [
            'enableLabel' => false,
            'options' => ['class' => ''],
            'inputOptions' => ['aria-label' => 'Verification code'],
        ])->widget(Captcha::class, [
            'template' => '<div class="d-flex align-items-center gap-2">{image}{input}</div>',
        ]) ?>

        <?= Html::submitButton(
            'Submit',
            [
                'class' => 'btn login-btn text-white px-4',
                'name' => 'contact-button',
            ],
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php endif; ?>
