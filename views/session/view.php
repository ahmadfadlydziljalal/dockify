<?php

use mdm\admin\components\Helper;
use yii\bootstrap5\ButtonDropdown;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Session */
/* @see app\controllers\SessionController::actionView() */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Session'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="session-view d-flex flex-column gap-3">

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="d-flex justify-content-between flex-wrap gap-4 gap-md-2">
            <h1 class="my-0"><?= Html::encode($this->title) ?></h1>
            <div class="d-flex flex-row flex-wrap align-items-center" style="gap: .5rem">
                <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Index'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::a(Yii::t('app', 'Create More'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php
                $items = [
                    [
                        'label'       => 'Update',
                        'url'         => ['update', 'id' => $model->id],
                        'linkOptions' => [
                            'data-pjax' => 0
                        ]
                    ],
                ];

                if (Helper::checkRoute('delete')) :
                    $items = array_merge($items, [
                        '<li><hr class="dropdown-divider"></li>',
                        [
                            'label'       => Yii::t('app', 'Delete'),
                            'url'         => ['delete', 'id' => $model->id],
                            'linkOptions' => [
                                'class' => 'text-danger',
                                'data'  => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method'  => 'post',
                                ],
                            ]
                        ]
                    ]);
                endif;

                echo ButtonDropdown::widget([
                    'label'         => '<i class="bi bi-three-dots-vertical"></i>',
                    'dropdown'      => [
                        'items'   => $items,
                        'options' => [
                            'class' => 'dropdown-menu dropdown-menu-end'
                        ]
                    ],
                    'buttonOptions' => [
                        'class'          => 'btn btn-outline-secondary dropdown-toggle text-body-emphasis',
                        'type'           => 'button',
                        'data-bs-toggle' => 'dropdown',
                        'aria-expanded'  => false
                    ],
                    'encodeLabel'   => false,
                ]);
                ?>
            </div>
        </div>
    <?php } ?>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'expire:datetime',
            [
                'attribute' => 'data',
                'format'    => 'ntext',
                'value'     => \yii\helpers\StringHelper::truncate($model->data, 128),
            ],
            [
                'attribute'      => 'user_id',
                'captionOptions' => ['class' => 'text-nowrap'],
                'value'          => $model->user ? $model->user->username : null
            ],
            'last_write:datetime',
        ],
    ]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="d-flex flex-row flex-wrap">
            <?= Html::a(Yii::t('app', 'Index'), ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    <?php } ?>

</div>
