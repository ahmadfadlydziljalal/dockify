<?php
declare(strict_types=1);
/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;

?>


<?php $this->beginContent('@app/views/layouts/clear.php') ?>
<main id="main" class="flex-grow-1" role="main">
    <div class="container p-0">
        <?= $content ?>
    </div>
</main>
<?php $this->endContent(); ?>
