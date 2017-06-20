<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Pickup;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\RepairOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拾物管理';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/pickup-work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('全部导出', ['pickup-export?' . Yii::$app->request->queryString], ['class' => 'btn btn-success']) ?>
            </p>


            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => "grid",
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"]],
                    [
                        'attribute' => 'type',
                        'value' =>
                        function($model) {
                            return $model->Type;   //主要通过此种方式实现
                        },
                        'filter' => Pickup::$List['type']
                    ],
                    'goods',
                    'address',
                    'content',
                    ['attribute' => 'end_at', 'format' => ["date", "php:Y-m-d H:i:s"], 'filter' => false,],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Pickup::$List['stat']
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view} {close}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'close' => function($url, $model, $key) {
                                if ($model->stat === Pickup::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-close"></i> 关闭', ['work/pickup-close', 'id' => $key], ['class' => 'btn btn-danger btn-xs',]);
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
    $('.view').on('click', function () {
        $('.modal-title').html('详情');
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