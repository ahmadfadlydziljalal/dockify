<?php
/* @var $this yii\web\View */

use app\models\Session;
use yii\helpers\Html;

return [
    /*[
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],*/
    [
        'class'       => 'kartik\grid\SerialColumn',
        'width'       => '30px',
        'mergeHeader' => false
    ],
//    [
//        'class'     => '\kartik\grid\DataColumn',
//        'attribute' => 'id',
//    ],
    [
        'class'          => '\kartik\grid\DataColumn',
        'attribute'      => 'user_id',
        'headerOptions'  => ['class' => 'text-nowrap'],
        'contentOptions' => ['class' => 'text-nowrap font-monospace'],
        'format'         => 'raw',
        'value'          => function (Session $model) {
            return $model->user ?
                Html::img($model->user->getPhoto(), [
                    'alt' => '',
                    'class' => 'img-thumbnail',
                    'width' => 36,
                ]) . ' ' . $model->user->username :
                null;
        }
    ],

    [
        'class'          => '\kartik\grid\DataColumn',
        'attribute'      => 'last_write',
        'format'         => ['datetime', 'php:d-m-Y H:i:s'],
        'headerOptions'  => ['class' => 'text-nowrap'],
        'contentOptions' => ['class' => 'text-nowrap font-monospace text-end'],
    ],

    [
        'class'          => '\kartik\grid\DataColumn',
        'attribute'      => 'expire',
        'format'         => ['datetime', 'php:d-m-Y H:i:s'],
        'headerOptions'  => ['class' => 'text-nowrap'],
        'contentOptions' => ['class' => 'text-nowrap font-monospace text-end'],
    ],
//    [
//        'class'          => '\kartik\grid\DataColumn',
//        'attribute'      => 'data',
//        'headerOptions'  => ['class' => 'text-nowrap'],
//        'contentOptions' => ['class' => 'text-nowrap'],
//    ],


    [
        'class'          => 'kartik\grid\ActionColumn',
        'template'       => '{view} {delete}',
        'headerOptions'  => [
            'style' => 'width: 2px;'
        ],
        'contentOptions' => [
            'class' => 'text-nowrap',
        ],
        'header'         => '',
        'viewOptions'    => [
            'label'       => '<i class="bi bi-eye"></i>',
            'role'        => 'modal-remote',
            'title'       => 'View',
            'data-toggle' => 'tooltip'
        ],
        'deleteOptions'  => [
            'label'                => '<i class="bi bi-trash text-danger"></i>',
            'class'                => 'text-danger',
            'role'                 => 'modal-remote',
            'title'                => 'Delete',
            'data-confirm'         => false,
            'data-method'          => false,// for override yii data api
            'data-request-method'  => 'post',
            'data-toggle'          => 'tooltip',
            'data-confirm-title'   => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ]
];
