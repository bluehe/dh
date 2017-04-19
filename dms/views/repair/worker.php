<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Room;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '人员管理';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repair-worker-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('添加人员', ['worker-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'unit_id',
                        'value' =>
                        function($model) {
                            return $model->unit_id ? $model->unit->name : '';   //主要通过此种方式实现
                        },
                    ],
                    'name',
                    'tel',
                    'email',
                    'address',
                    'note',
                    [
                        'attribute' => 'type',
                        'value' =>
                        function($model) {
                            return implode(',', $model->get_type_id($model->get_worker_type($model->id)));   //主要通过此种方式实现
                        },
                    ],
                    [
                        'attribute' => 'area',
                        'value' =>
                        function($model) {
                            return implode(',', Room::get_forum_id($model->get_worker_area($model->id)));   //主要通过此种方式实现
                        },
                    ],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                    ],
                    [
                        'attribute' => 'uid',
                        'value' =>
                        function($model) {
                            return $model->uid ? $model->user->name : '';   //主要通过此种方式实现
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['worker-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['worker-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '此操作不可恢复，你确定要删除人员吗？',]]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>