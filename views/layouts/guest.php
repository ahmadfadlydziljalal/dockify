<?php
declare(strict_types=1);
/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;

?>


<?php $this->beginContent('@app/views/layouts/clear.php') ?>
<?= $this->render('_header') ?>

<main id="main" class="flex-grow-1" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?= $this->render('_footer') ?>

<?php $this->endContent(); ?>
