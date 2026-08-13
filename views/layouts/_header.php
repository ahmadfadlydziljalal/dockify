<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$items = [
    [
        'label' => 'Home',
        'url'   => ['/site/index'],
    ],
    [
        'label' => 'About',
        'url'   => ['/site/about'],
    ],
    [
        'label' => 'Contact',
        'url'   => ['/site/contact'],
    ],

];

?>
<header id="header">
    <?php NavBar::begin(
        [
            'brandLabel' => Yii::$app->params['appIcon'] . ' <span class="ms-2">' . Yii::$app->name . '</span>',
            'brandUrl'   => Yii::$app->homeUrl,
            'options'    => ['class' => 'navbar-expand-md bg-body-tertiary shadow-sm fixed-top']
        ],
    ) ?>
    <?= Nav::widget(
        [
            'options'      => ['class' => 'navbar-nav me-auto'],
            'encodeLabels' => false,
            'items'        => $items,
        ],
    ) ?>

    <?=
    Nav::widget([
        'encodeLabels'    => false,
        'options'         => ['class' => 'navbar-nav ml-auto'],
        'activateParents' => true,
        'items'           => [
            Html::button(
                '&#127769;',
                [
                    'id'         => 'theme-toggle',
                    'class'      => 'btn btn-link nav-link',
                    'aria-label' => 'Switch to dark mode',
                ],
            ),
            [
                'label'   => 'Login',
                'url'     => ['/site/login'],
                'visible' => Yii::$app->user->isGuest,
            ],
            [
                'label'       => 'Logout (' . Html::encode(Yii::$app->user->identity?->username ?? '') . ')',
                'url'         => ['/site/logout'],
                'linkOptions' => [
                    'data-method' => 'post',
                    'class'       => 'nav-link logout',
                ],
                'visible'     => !Yii::$app->user->isGuest,
            ],
        ]
    ]);
    ?>

    <?php NavBar::end() ?>
</header>
