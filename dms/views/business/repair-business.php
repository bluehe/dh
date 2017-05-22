<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\RepairOrder;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\RepairOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '网上报修';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['business/repair-business']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repair-order-index">

    <div class="box box-primary">
        <div class="box-body">


            <p>
                <?= Html::a('我要报修', ['repair-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    'serial',
                    'created_at:datetime',
                    [
                        'attribute' => 'repair_type',
                        'value' =>
                        function($model) {
                            return $model->repair_type ? $model->type->v : $model->repair_type;
                        },
                    ],
                    [
                        'attribute' => 'repair_area',
                        'value' =>
                        function($model) {
                            return $model->repair_area ? $model->area->name : $model->repair_area;
                        },
                    ],
                    'address',
                    //'title',
                    'content',
//                    [
//                        "attribute" => "created_at",
//                        "format" => ["date", "php:Y-m-d H:i:s"],
//                    ],
                    // 'accept_uid',
                    // 'repair_at',
                    // 'repair_uid',
                    // 'worker_id',
                    // 'evaluate_at',
                    // 'note',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view} {update} {close} {evaluate}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'update' => function($url, $model, $key) {
                                if ($model->stat == RepairOrder::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-pencil"></i> 修改', ['repair-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                                }
                            },
                            'close' => function($url, $model, $key) {
                                if ($model->stat == RepairOrder::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-close"></i> 取消', ['repair-close', 'id' => $key], ['class' => 'btn btn-danger btn-xs',]);
                                }
                            },
                            'evaluate' => function($url, $model, $key) {
                                if ($model->stat == RepairOrder::STAT_REPAIRED) {
                                    return Html::a('<i class="fa fa-star-o"></i> 评价', '#', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#view-modal',
                                                'class' => 'btn btn-info btn-xs evaluate',
                                    ]);
                                }
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>        </div>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'view-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('view') ?>
    $('.view').on('click', function () {
        $('.modal-title').html('报修详情');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('repair-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.evaluate').on('click', function () {
        $('.modal-title').html('报修评价');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('repair-evaluate') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>