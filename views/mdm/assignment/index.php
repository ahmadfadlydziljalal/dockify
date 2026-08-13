<?php


use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class'         => 'yii\grid\ActionColumn',
    'template'      => '{view}',
    'headerOptions' => [
        'style' => 'width: 2px;'
    ],
];


$columns[1] = [
    'attribute'        => 'username',
];



?>
<div class="assignment-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    GridView::widget([
        'id'           => 'crud-datatable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
    ]);
    ?>

</div>
