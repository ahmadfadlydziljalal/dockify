<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = Yii::$app->name;
$this->params['meta_description'] = 'A high-performance PHP framework best for developing web applications. Fast, secure, and professional.';
$this->params['meta_keywords'] = 'yii, yii2, php, framework, web application, high-performance';
?>
<div class="site-welcome">
    <?= $this->render('_hero') ?>
    <?= $this->render('_extensions') ?>
</div>
