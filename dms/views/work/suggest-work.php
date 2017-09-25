<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Suggest;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\RepairOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '投诉管理';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/pickup-work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suggest-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('全部导出', ['suggest-export?' . Yii::$app->request->queryString], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => "grid",
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    'serial',
                    ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"]],
                    [
                        'attribute' => 'type',
                        'value' =>
                        function($model) {
                            return $model->Type;   //主要通过此种方式实现
                        },
                        'filter' => Suggest::$List['type']
                    ],
                    'content',
                  
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Suggest::$List['stat']
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view} {reply}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'reply' => function($url, $model, $key) {
                                if ($model->stat === Suggest::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-send"></i> 回复', '#', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#view-modal',
                                                'class' => 'btn btn-warning btn-xs reply',
                                    ]);
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
    $('.suggest-index').on('click', '.view', function () {
        $('.modal-title').html('详情');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.suggest-index').on('click', '.reply', function () {
        $('.modal-title').html('回复');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-reply') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>