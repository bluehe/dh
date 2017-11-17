<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\grid\GridView;
use dh\models\Suggest;
use yii\helpers\Html;

$this->title = '首页';

mb_regex_encoding("UTF-8");
?>

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <div class="box box-primary suggestorder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-commenting-o"></i> 建议反馈</h3>


            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <?php
                Pjax::begin();
                ?>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <?=
                GridView::widget([
                    'dataProvider' => $suggest,
                    'layout' => "<div class=table-responsive>{items}</div>\n{pager}",
                    'pager' => [
                        'options' => ['class' => 'pagination pagination-sm inline']
                    ],
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
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>

            </div>
            <!-- /.box-body -->
        </div>

    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">
<?php Pjax::begin(); ?>
        <div class="nav-tabs-custom" style="cursor: move;">

            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li><a href="#enternews" data-toggle="tab">娱乐</a></li>
                <li><a href="#mil" data-toggle="tab">军事</a></li>

                <li class="active"><a href="#internet" data-toggle="tab">互联网</a></li>
                <li class="pull-left header"><i class="fa fa-newspaper-o"></i> 新闻</li>
            </ul>
            <div class="tab-content">
                <div class="baidunews tab-pane" id="enternews" >
                    <script language="JavaScript" type="text/JavaScript" src="//news.baidu.com/n?cmd=1&class=enternews&pn=1&tn=newsbrofcu"></script>
                </div>
                <div class="baidunews tab-pane" id="mil" >
                    <script language="JavaScript" type="text/JavaScript" src="//news.baidu.com/n?cmd=1&class=mil&pn=1&tn=newsbrofcu"></script>
                </div>

                <div class="baidunews tab-pane active" id="internet" >
                    <script language="JavaScript" type="text/JavaScript" src="//news.baidu.com/n?cmd=1&class=internet&pn=1&tn=newsbrofcu"></script>
                </div>

            </div>
        </div>
<?php Pjax::end(); ?>

    </section>
    <!-- right col -->
</div>
<!-- /.row (main row) -->
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
<?php $this->beginBlock('index') ?>
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['index'], \yii\web\View::POS_END); ?>
