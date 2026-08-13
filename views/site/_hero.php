<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<!-- Hero banner with Yii gradient -->
<div class="hero-banner text-white rounded-4 p-5 mb-4 position-relative overflow-hidden">
    <?= Html::img(Yii::getAlias('@web/images/yii3_full_white_for_dark.svg'), [
        'alt' => '',
        'class' => 'd-none d-lg-block position-absolute hero-logo',
    ]) ?>
    <div class="position-relative">
        <h1 class="display-5 fw-bold mb-3">Dzil`s Starter Kit</h1>
        <p class="lead opacity-75 mb-4 hero-lead">
            Powered By Yii2, A high-performance PHP framework best for developing web applications.
            Fast, secure, and professional.
        </p>
        <div class="d-flex gap-2 flex-wrap">
            <?= Html::a(
                'Get Started',
                'https://www.yiiframework.com/doc/guide/2.0/en/start-installation',
                [
                    'class' => 'btn btn-light btn-lg fw-semibold px-4',
                    'rel' => 'noopener',
                    'target' => '_blank',
                ],
            ) ?>
            <?= Html::a(
                'API Reference',
                'https://www.yiiframework.com/doc/api/2.0',
                [
                    'class' => 'btn btn-outline-light btn-lg px-4',
                    'rel' => 'noopener',
                    'target' => '_blank',
                ],
            ) ?>
        </div>
    </div>
</div>
