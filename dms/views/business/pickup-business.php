<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Pickup;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\PickupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拾物招领';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['business/repair-business']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-index">

    <div class="box box-primary">
        <div class="box-body">


            <p>
                <?= Html::a('发布信息', ['pickup-create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    'created_at:datetime',
                    [
                        'attribute' => 'type',
                        'value' =>
                        function($model) {
                            return $model->Type;   //主要通过此种方式实现
                        },
                    ],
                    'goods',
                    'address',
                    'content',
                    'end_at:datetime',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view} {update} {success} {fail}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'update' => function($url, $model, $key) {
                                if ($model->stat === Pickup::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-pencil"></i> 修改', ['pickup-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                                }
                            },
                            'success' => function($url, $model, $key) {
                                if ($model->stat === Pickup::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-check"></i> 成功', ['pickup-close', 'id' => $key, 'stat' => Pickup::STAT_SUCCESS], ['class' => 'btn btn-info btn-xs',]);
                                }
                            },
                            'fail' => function($url, $model, $key) {
                                if ($model->stat === Pickup::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-remove"></i> 失败', ['pickup-close', 'id' => $key, 'stat' => Pickup::STAT_FAIL], ['class' => 'btn btn-danger btn-xs',]);
                                }
                            },
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
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
    $('.view').on('click', function () {
        $('.modal-title').html('拾物招领');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('pickup-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>