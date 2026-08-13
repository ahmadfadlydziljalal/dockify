<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;

?>

<footer class="dockify-footer">
    <div class="d-flex justify-content-between align-items-center">
        <span class="small">&copy; <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></span>
        <a href="https://www.yiiframework.com/" rel="external" class="text-body-secondary text-decoration-none small">
            <?= Yii::t('yii', 'Powered by {yii}', ['yii' => ''],) ?>
            <?= Html::img('@web/images/yii3_full_for_light.svg', ['alt'    => 'Yii Framework', 'class'  => 'align-text-bottom footer-logo-light', 'height' => '28',],) ?>
            <?= Html::img('@web/images/yii3_full_for_dark.svg', ['alt'    => 'Yii Framework', 'class'  => 'align-text-bottom footer-logo-dark', 'height' => '28',],) ?>
        </a>
    </div>

</footer>
