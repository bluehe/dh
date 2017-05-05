<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Room;
use yii\bootstrap\Modal;

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
                    [
                        'attribute' => 'role',
                        'value' =>
                        function($model) {
                            return $model->Role;   //主要通过此种方式实现
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
                            return $model->get_worker_type($model->id) ? implode(',', $model->get_type_id($model->get_worker_type($model->id))) : '';   //主要通过此种方式实现
                        },
                    ],
                    [
                        'attribute' => 'area',
                        'value' =>
                        function($model) {
                            return $model->get_worker_area($model->id) ? implode(',', Room::get_forum_id($model->get_worker_area($model->id))) : '';   //主要通过此种方式实现
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
                            return $model->uid ? $model->user->username : Html::a('绑定用户', '#', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#binduser-modal',
                                        'class' => 'btn btn-success btn-xs user-bind',
                                        'data-id' => $model->id,
                            ]);
                            //主要通过此种方式实现
                        },
                        'format' => 'raw',
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'binduser-modal',
    'header' => '<h4 class="modal-title">绑定用户</h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('bind') ?>
    $('.user-bind').on('click', function () {
        $.get('<?= Url::toRoute('bind') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['bind'], \yii\web\View::POS_END); ?>
