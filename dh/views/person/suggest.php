<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dh\models\Suggest;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '建议反馈';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['suggest']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suggest-index">

    <div class="box box-primary">
        <div class="box-body">
            <p>
                <?= Html::a('我要留言', '#', ['data-toggle' => 'modal', 'data-target' => '#view-modal', 'class' => 'btn btn-success create',]) ?>
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
                    'created_at:datetime',
                    'content:ntext',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => ($model->stat == Suggest::STAT_OPEN ? 'text-aqua' : ($model->stat == Suggest::STAT_REPLY ? 'text-green' : 'text-red') )]);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view} {update} {close}', //只需要展示删除和更新
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-eye"></i> 详情', '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'btn btn-success btn-xs view',
                                ]);
                            },
                            'update' => function($url, $model, $key) {
                                if ($model->stat === Suggest::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-pencil"></i> 修改', '#', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#view-modal',
                                                'class' => 'btn btn-primary btn-xs update',
                                    ]);
                                }
                            },
                            'close' => function($url, $model, $key) {
                                if ($model->stat === Suggest::STAT_OPEN) {
                                    return Html::a('<i class="fa fa-close"></i> 取消', ['suggest-close', 'id' => $key], ['class' => 'btn btn-danger btn-xs',]);
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
        $('.modal-title').html('建议反馈');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.suggest-index').on('click', '.create', function () {
        $('.modal-title').html('建议反馈');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-create') ?>',
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $('.suggest-index').on('click', '.update', function () {
        $('.modal-title').html('修改');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('suggest-update') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['view'], \yii\web\View::POS_END); ?>