<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Teacher;
use dms\models\Major;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '教职工管理';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/teacher']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-index">

    <div class="box box-primary">
        <div class="box-body">

            <p>
                <?= Html::a('添加教职工', ['teacher-create'], ['class' => 'btn btn-success']) ?>
            </p>
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
                    'name',
                    ['attribute' => 'gender',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Gender;   //主要通过此种方式实现
                        },
                        'filter' => Teacher::$List['gender'], //此处我们可以将筛选项组合成key-value形式
                    ],
                    [
                        'attribute' => 'college',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->college0 ? $model->college0->name : $model->college0;   //主要通过此种方式实现
                        },
                        'filter' => Major::get_major_college(), //此处我们可以将筛选项组合成key-value形式
                    ],
                    'tel',
                    'email:email',
                    'address',
                    'note',
                    [
                        'attribute' => 'stat',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Teacher::$List['stat'], //此处我们可以将筛选项组合成key-value形式
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
                        'filter' => false,
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['teacher-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['teacher-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '删除教职工将会影响相关学生，此操作不能恢复，你确定要删除教职工吗？',]]);
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
        $.get('<?= Url::toRoute('teacher-bind') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['bind'], \yii\web\View::POS_END); ?>