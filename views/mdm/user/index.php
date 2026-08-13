<?php

use mdm\admin\components\Helper;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index d-flex flex-column gap-3">

    <div class="d-flex justify-content-between align-items-center">
        <h1 class="my-0"><?= Html::encode($this->title) ?></h1>
        <div class="ms-md-auto ms-lg-auto">
            <?= Html::a('<i class="bi bi-plus"></i> Create', ['signup'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('<i class="bi bi-arrow-left"></i> Switch', ['/user/switch-identity'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return $model->status == 0 ?
                        Html::tag('span', 'Inactive', ['class' => 'badge bg-secondary']) :
                        'Active';
                },
                'filter'    => [
                    0  => 'Inactive',
                    10 => 'Active'
                ]
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn([
                    'activate',
                    'assignment',
                    //'divider',
                    'view',
                    'delete'
                ]),
                /* 'dropdown'        => true,
                 'dropdownOptions' => [
                     'class'          => 'dropstart me-2',
                     'data-bs-offset' => '4,0',
                 ],*/
                'buttons'  => [
                    'header'     => fn($url, $model, $key) => '<div class="dropdown-header">' . $model->number . '</div>',
                    'divider'    => fn($url, $model, $key) => '<div class="dropdown-divider"></div>',
                    'assignment' => function ($url, $model) {
                        return Html::a(
                            '<i class="bi bi-sign-turn-right"></i>',
                            ['assignment/view', 'id' => $model->id],
                            [
                                'title'       => 'Receive',
                                'data-toggle' => 'tooltip',
                            ]
                        );
                    },
                    'activate'   => function ($url, $model) {

                        if ($model->status == 10) {
                            return '';
                        }

                        $options = [
                            'class'        => '',
                            'title'        => Yii::t('rbac-admin', 'Activate'),
                            'aria-label'   => Yii::t('rbac-admin', 'Activate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                            'data-method'  => 'post',
                            'data-pjax'    => '0',
                        ];
                        return Html::a('<i class="bi bi-check"></i> Activate', $url, $options);
                    },
                    'delete'     => function ($url, $model) {

                        if ($model->id === 1) {
                            return '';
                        }

                        $options = [
                            'label'        => '<i class="bi bi-trash text-danger"></i>',
                            'class'        => 'text-danger',
                            'title'        => Yii::t('rbac-admin', 'Delete'),
                            'aria-label'   => Yii::t('rbac-admin', 'Delete'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to delete this user?'),
                            'data-method'  => 'post',
                            'data-pjax'    => '0',
                        ];
                        return Html::a('<i class="bi bi-trash"></i>', $url, $options);
                    }
                ],
            ],
        ],
    ]);
    ?>
</div>
