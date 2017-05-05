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

$this->title = '报修管理';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/repair-work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repair-order-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('全部导出', ['repair-export?' . Yii::$app->request->queryString], ['class' => 'btn btn-success']) ?>
            </p>


            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => "grid",
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'showFooter' => true, //设置显示最下面的footer
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'id',
                        'footer' => (Yii::$app->user->can('报修管理') ? Html::a('<i class="fa fa-pencil"></i> 批量受理', 'javascript:void(0);', ['class' => 'btn btn-primary btn-xs acceptall']) . ' ' : '') . Html::a('<i class="fa fa-power-off"></i> 批量完工', 'javascript:void(0);', ['class' => 'btn btn-info btn-xs repairall']),
                        'footerOptions' => ['colspan' => 12],
                    ],
                    ['attribute' => 'serial', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"], 'footerOptions' => ['class' => 'hide'],],
                    [
                        'attribute' => 'repair_type',
                        'value' =>
                        function($model) {
                            return $model->repair_type ? $model->type->v : $model->repair_type;
                        },
                        'filter' => RepairOrder::get_repair_type(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'repair_area',
                        'value' =>
                        function($model) {
                            return $model->repair_area ? $model->area->name : $model->repair_area;
                        },
                        'filter' => RepairOrder::get_repair_area(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['attribute' => 'address', 'footerOptions' => ['class' => 'hide'],],
//                    ['attribute' => 'title', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'content', 'footerOptions' => ['class' => 'hide'],],
                    [
                        'attribute' => 'worker_id',
                        'value' =>
                        function($model) {
                            return $model->worker_id ? $model->worker->name : $model->worker_id;
                        },
                        'filter' => RepairOrder::get_repair_worker(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    // 'evaluate',
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
                        'attribute' => 'evaluate',
                        'value' =>
                        function($model) {
                            return $model->evaluate ? $model->Evaluate : NULL;   //主要通过此种方式实现
                        },
                        'filter' => RepairOrder::$List['evaluate'],
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => RepairOrder::$List['stat'],
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'footerOptions' => ['class' => 'hide'],
                        'template' => '{view} {accept} {dispatch} {repair}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'accept' => function($url, $model, $key) {
                                if ($model->stat === RepairOrder::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-pencil"></i> 受理', '#', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#view-modal',
                                                'class' => 'btn btn-primary btn-xs accept',
                                    ]);
                                }
                            },
                            'dispatch' => function($url, $model, $key) {
                                if (($model->stat === RepairOrder::STAT_ACCEPT || $model->stat == RepairOrder::STAT_DISPATCH) && Yii::$app->user->can('报修管理')) {
                                    return Html::a($model->stat === RepairOrder::STAT_ACCEPT ? '<i class="fa fa-send"></i> 派工' : '<i class="fa fa-send"></i> 重新派工', '#', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#view-modal',
                                                'class' => 'btn btn-warning btn-xs dispatch',
                                    ]);
                                }
                            },
                            'repair' => function($url, $model, $key) {
                                if ($model->stat == RepairOrder::STAT_DISPATCH) {
                                    return Html::a('<i class="fa fa-power-off"></i> 完工', ['repair-repair', 'id' => $key], ['class' => 'btn btn-info btn-xs',]);
                                }
                            },
                        ],
                    ],
                ],
            ]);
            ?>
        </div>
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
    $(".repair-order-index").on("click", '.acceptall', function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if (keys.length > 0 && confirm("确定批量受理么？")) {
            $.post("<?= Yii::$app->urlManager->createUrl('work/repair-accepts') ?>?ids=" + keys, function (data) {
                if (data) {
                    window.location.reload();
                }
            });
        }
    });
    $(".repair-order-index").on("click", '.repairall', function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if (keys.length > 0 && confirm("确定批量完工么？")) {
            $.post("<?= Yii::$app->urlManager->createUrl('work/repair-repairs') ?>?ids=" + keys, function (data) {
                if (data) {
                    window.location.reload();
                }
            });
        }
    });
    $('.view').on('click', function () {
        $('.modal-title').html('报修详情');
        $.get('<?= Url::toRoute('repair-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.accept').on('click', function () {
        $('.modal-title').html('报修受理');
        $.get('<?= Url::toRoute('repair-accept') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.dispatch').on('click', function () {
        $('.modal-title').html('报修派工');
        $.get('<?= Url::toRoute('repair-dispatch') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>