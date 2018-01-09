<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dh\models\Website;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dh\models\WebsiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '网址管理';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">

    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <p>
                <?= Html::a('添加网址', ['#'], ['data-toggle' => 'modal', 'data-target' => '#website-modal', 'class' => 'btn btn-success website-create']) ?>
            </p>
            <?php Pjax::begin(); ?>                            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n<div class=table-responsive>{items}</div>\n{pager}",
                'summary' => "第{begin}-{end}条，共{totalCount}条",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => '用户',
                        'attribute' => 'u.username',
                        'value' =>
                        function($model) {
                            return $model->c->uid ? $model->u['username'] : '';
                        },
                        'headerOptions' => ['width' => '80'],
                    ],
                    [
                        'label' => '分类',
                        'attribute' => 'c.title',
                        'headerOptions' => ['width' => '80'],
                    ],
                    'title',
                    [
                        'attribute' => 'url',
                        'format' => 'url',
                        'contentOptions' => ['style' => 'word-wrap:break-word;max-width:450px'],
                    ],
                    // 'sort_order',
                    // 'share_id',
                    // 'collect_num',
                    'click_num',
                    // 'created_at',
                    // 'updated_at',
                    [
                        'attribute' => 'share_status',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->ShareStatus, ['class' => ($model->share_status == Website::SHARE_DEFAULT ? 'text-green' : 'text-red')]);
                        },
                        'format' => 'raw',
                        'filter' => Website::$List['share_status'],
                        'headerOptions' => ['width' => '80'],
                    ],
                    [
                        'attribute' => 'is_open',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->IsOpen, ['class' => ($model->is_open == Website::ISOPEN_OPEN ? 'text-green' : 'text-yellow')]);
                        },
                        'format' => 'raw',
                        'filter' => Website::$List['is_open'],
                        'headerOptions' => ['width' => '80'],
                    ],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return Html::tag('span', $model->Stat, ['class' => ($model->stat == Website::STAT_OPEN ? 'text-green' : 'text-red')]);
                        },
                        'format' => 'raw',
                        'filter' => Website::$List['stat'],
                        'headerOptions' => ['width' => '80'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete} {black}',
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return $model->c->uid ? '' : Html::a('<i class="fa fa-pencil"></i> 修改', ['#'], ['data-toggle' => 'modal', 'data-target' => '#website-modal', 'class' => 'btn btn-primary btn-xs website-update',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return $model->c->uid ? '' : Html::a('<i class="fa fa-trash-o"></i> 删除', ['website-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '确定删除吗？',]]);
                            },
                            'black' => function($url, $model, $key) {
                                return $model->c->uid && $model->stat != Website::STAT_BLACK ? Html::a('<i class="fa fa-warning"></i> 拉黑', ['website-black', 'id' => $key], ['class' => 'btn btn-warning btn-xs', 'data' => ['confirm' => '确定拉黑吗？',]]) : '';
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
    'id' => 'website-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('website') ?>
    $('.website-index').on('click', '.website-create', function () {
        $('.modal-title').html('添加网址');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('website-create') ?>',
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });

    $('.website-index').on('click', '.website-update', function () {
        $('.modal-title').html('修改网址');
        $('.modal-body').html('');
        $.get('<?= Url::toRoute('website-update') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['website'], \yii\web\View::POS_END); ?>