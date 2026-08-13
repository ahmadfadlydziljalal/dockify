<?php
declare(strict_types=1);
/** @var yii\web\View $this */

/** @var string $content */

use app\widgets\Alert;
use mdm\admin\components\MenuHelper;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use app\components\MenuHelper as MenuFormatterHelper;

// Get Menu Items with root=2 for sidebar
if (empty($this->params['menuSidebarItems'])) {
    $this->params['menuSidebarItems'] = MenuHelper::getAssignedMenu(
        (int) Yii::$app->user->id,
        2,
        MenuFormatterHelper::getMenuFormatter()
    );
}

// Get menu items with root=3 for avatar dropdown
if (empty($this->params['menuAvatarRightItems'])) {
    $this->params['menuAvatarRightItems'] = MenuHelper::getAssignedMenu(
        (int) Yii::$app->user->id,
        3,
        MenuFormatterHelper::getMenuFormatter()
    );
}

$this->params['menuSearchTypeAhead'] = MenuFormatterHelper::mapMenuItemsWithParents(
        array_merge(
            $this->params['menuAvatarRightItems'] ?? [],
            $this->params['menuSidebarItems'] ?? [],
        )
    );

?>


<?php $this->beginContent('@app/views/layouts/clear.php') ?>

<div class="dockify-layout">

    <?= $this->render('_main_sidebar') ?>

    <div class="dockify-main-panel">
        <?= $this->render('_main_header') ?>
        <main id="main" class="dockify-content" role="main">
            <div class="dockify-content-inner">
                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget(
                        [
                            'tag' => 'ol',
                            'options' => ['class' => 'dockify-breadcrumb list-unstyled mb-3 d-lg-none'],
                            'homeLink' => [
                                'label' => Html::encode('Home'),
                                'url' => Yii::$app->homeUrl,
                            ],
                            'links' => $this->params['breadcrumbs'],
                        ],
                    ) ?>
                <?php endif ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>
        <?= $this->render('_main_footer') ?>
    </div>
</div>

<?php $this->endContent(); ?>
