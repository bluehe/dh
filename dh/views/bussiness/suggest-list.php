<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use dh\models\Suggest;
use dh\models\User;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '建议反馈';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suggest-index">

    <div class="box box-primary">
        <div class="box-body">

            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'created_at',
                        'value' =>
                        function($model) {
                            return date('Y-m-d H:i:s', $model->created_at);   //主要通过此种方式实现
                        },
                        'filter' => DateRangePicker::widget([
                            'name' => 'UserSearch[created_at]',
                            'useWithAddon' => true,
                            'presetDropdown' => true,
                            'convertFormat' => true,
                            'value' => Yii::$app->request->get('UserSearch')['created_at'],
                            'pluginOptions' => [
                                'timePicker' => false,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                    'separator' => '至'
                                ],
                                'linkedCalendars' => false,
                            ],
                        ]),
                        'headerOptions' => ['width' => '235'],
                    ],
                    [
                        'attribute' => 'uid',
                        'value' =>
                        function($model) {
                            return User::get_nickname($model->uid);
                        },
                        'filter' => false,
                    ],
                    'content:ntext',
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => ($model->stat == Suggest::STAT_OPEN ? 'text-aqua' : ($model->stat == Suggest::STAT_REPLY ? 'text-green' : 'text-red') )]);
                        },
                        'format' => 'raw',
                        'filter' => Suggest::$List['stat'],
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