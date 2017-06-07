<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use dms\models\Student;
use dms\models\Major;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel dms\models\StudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生管理';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['student/student']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-index">

    <div class="box box-primary">
        <div class="box-body">


            <p>
                <?= Html::a('添加学生', ['student-create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fa fa-share-square-o"></i>全部导出', ['student-export?' . Yii::$app->request->queryString], ['class' => 'btn btn-warning pull-right', 'style' => 'margin-left:10px;']) ?>
                <span class="pull-right">
                    <?=
                    FileInput::widget([
                        'name' => 'files[]',
                        'pluginOptions' => [
                            'language' => 'zh',
                            'layoutTemplates' => ['progress' => ''],
                            //关闭按钮
                            'showPreview' => false,
                            'showCancel' => false,
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            //浏览按钮样式
                            'browseClass' => 'btn btn-primary',
                            'browseLabel' => '导入名单',
                            //错误提示
                            'elErrorContainer' => false,
                            //进度条
                            //'progressClass' => 'hide',
                            //'progressUploadThreshold' => 'hide',
                            //上传
                            'uploadAsync' => true,
                            'uploadUrl' => Url::toRoute(['student/student-import']),
                            'maxFileSize' => $maxsize,
                        ],
                        'options' => ['accept' => 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                        'pluginEvents' => [
                            //选择后直接上传
                            'change' => 'function() {$(this).fileinput("upload");}',
                            //上传成功
                            'fileuploaded' => 'function(event, data) {window.location.reload();}',
                        ],
                    ]);
                    ?>
                </span>

            </p>
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'export' => false,
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
                        'footer' => Html::a('<i class="fa fa-trash-o"></i> 批量删除', 'javascript:void(0);', ['class' => 'btn btn-danger btn-xs deleteall']),
                        'footerOptions' => ['colspan' => 14],
                    ],
                    ['attribute' => 'name', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'stuno', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'gender',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->Gender;   //主要通过此种方式实现
                        },
                        'filter' => Student::$List['gender'], //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'college',
                        'value' => //'colleges.name',
                        function($model) {
                            return $model->college ? $model->college0->name : $model->college;   //主要通过此种方式实现
                        },
                        'filter' => Major::get_college_id(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'major',
                        'value' =>
                        function($model) {
                            return $model->major ? $model->major0->name : $model->major;   //主要通过此种方式实现
                        },
                        'filter' => Student::get_major(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'grade',
                        'value' =>
                        function($model) {
                            return $model->grade ? $model->grade0->v : $model->grade;   //主要通过此种方式实现
                        },
                        'filter' => Student::get_grade(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    [
                        'attribute' => 'teacher',
                        'value' =>
                        function($model) {
                            return $model->teacher ? $model->teacher0->name : $model->teacher;   //主要通过此种方式实现
                        },
                        'filter' => Student::get_teacher(), //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['attribute' => 'tel', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'email', 'footerOptions' => ['class' => 'hide'],],
                    ['attribute' => 'note', 'footerOptions' => ['class' => 'hide'],],
                    [
                        'attribute' => 'stat',
                        'value' =>
                        function($model) {
                            return $model->Stat;   //主要通过此种方式实现
                        },
                        'filter' => Student::$List['stat'], //此处我们可以将筛选项组合成key-value形式
                        'footerOptions' => ['class' => 'hide'],
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
                        'footerOptions' => ['class' => 'hide'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}', //只需要展示删除和更新
                        'buttons' => [
                            'update' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-pencil"></i> 修改', ['student-update', 'id' => $key], ['class' => 'btn btn-primary btn-xs',]);
                            },
                            'delete' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-trash-o"></i> 删除', ['student-delete', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data' => ['confirm' => '此操作不能恢复，你确定要删除学生吗？',]]);
                            },
                        ],
                        'footerOptions' => ['class' => 'hide'],
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
        $.get('<?= Url::toRoute('student-bind') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
    $(".student-index").on("click", '.deleteall', function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if (keys.length > 0 && confirm("此操作不能恢复，你确定要删除吗？")) {
            $.post("<?= Yii::$app->urlManager->createUrl('student/student-deletes') ?>?ids=" + keys, function (data) {
                if (data) {
                    window.location.reload();
                }
            });
        }
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['bind'], \yii\web\View::POS_END); ?>