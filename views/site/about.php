<?php

/** @var yii\web\View $this */
/** @see \app\controllers\SiteController::actionAbout() */
use yii\helpers\Html;
use cebe\markdown\GithubMarkdown;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Learn more about this ' . Yii::$app->name . '-powered application.';
$this->params['meta_keywords'] = Yii::$app->name . ', yii2, about, php, framework';
$readmePath = Yii::getAlias('@app/README-DOCKIFY.md');
$readmeContent = is_file($readmePath) ? file_get_contents($readmePath) : '';
$readmeHtml = (new GithubMarkdown())->parse($readmeContent);
?>

<div class="site-about">
    <h1 class="h3 fw-bold mb-3"><?= Html::encode($this->title) ?></h1>
    <?= $readmeHtml ?>
</div>
