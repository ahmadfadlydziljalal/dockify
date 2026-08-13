<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;
use yii\grid\GridView;
use pheme\settings\Module;
use pheme\settings\models\Setting;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\SettingSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Module::t('settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a( '<i class="bi bi-plus-circle"></i> ' . Module::t('settings', 'Create', ['modelClass' => Module::t('settings', 'Setting'),]), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php //echo $this->render('_search', ['model' => $searchModel]);?>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => \yii\grid\SerialColumn::class
                ],
                // 'id',
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(
                        Setting::find()->select('section')->distinct()->where(['<>', 'section', ''])->all(),
                        'section',
                        'section'
                    ),
                ],
                'key',
                [
                    'attribute' => 'value',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return nl2br($model->value);
                    },
                    'contentOptions' => [
                        'class' => 'text-wrap',
                        'style' => [
                            'max-width' => '128em',
                        ]
                    ]
                ],
                'type',
                [
                    'class' => '\pheme\grid\ToggleColumn',
                    'attribute' => 'active',
                    'filter' => [1 => Yii::t('yii', 'Yes'), 0 => Yii::t('yii', 'No')],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
