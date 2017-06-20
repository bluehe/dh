<?php
/* @var $this yii\web\View */

use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use dms\models\RepairOrder;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '首页';

mb_regex_encoding("UTF-8");
?>
<!-- Small boxes (Stat box) -->
<?php if (Yii::$app->user->can('楼苑设置')) { ?>
    <div class="row">
        <div class="col-md-3 col-xs-6">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-building-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">楼苑</span>
                    <span class="info-box-number"><?= $total['building'] ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- ./col -->
        <div class="col-md-3 col-xs-6">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-home"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">房间</span>
                    <span class="info-box-number"><?= $total['broom'] ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- ./col -->
        <div class="col-md-3  col-xs-6">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-bed"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">床位</span>
                    <span class="info-box-number"><?= $total['bed'] ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- ./col -->
        <div class="col-md-3 col-xs-6">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">用户</span>
                    <span class="info-box-number"><?= $total['user'] ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
<?php } ?>
<!-- ./col -->

<!-- /.row -->

<?php if (Yii::$app->user->can('维修管理')) { ?>
    <div class="row">
        <div class="col-md-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= $total['repair_today'] ?></h3>
                    <p>今日报修</p>
                </div>
                <div class="icon">
                    <i class="fa fa-wrench"></i>
                </div>
                <a href="<?= \yii\helpers\Url::toRoute(['work/repair-work', 'RepairOrderSearch[created_at]' => date('Y-m-d', time())]) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            <!-- /.info-box -->
        </div>
        <!-- ./col -->
        <?php if (Yii::$app->user->can('报修管理')) { ?>
            <div class="col-md-3 col-xs-6">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3><?= isset($total['repair'][RepairOrder::STAT_OPEN]) ? $total['repair'][RepairOrder::STAT_OPEN] : 0 ?></h3>
                        <p>待受理</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-wrench"></i>
                    </div>
                    <a href="<?= \yii\helpers\Url::toRoute(['work/repair-work', 'RepairOrderSearch[stat]' => RepairOrder::STAT_OPEN]) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <!-- /.info-box -->
            </div>
            <!-- ./col -->
            <div class="col-md-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= isset($total['repair'][RepairOrder::STAT_ACCEPT]) ? $total['repair'][RepairOrder::STAT_ACCEPT] : 0 ?></h3>
                        <p>待派工</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-wrench"></i>
                    </div>
                    <a href="<?= \yii\helpers\Url::toRoute(['work/repair-work', 'RepairOrderSearch[stat]' => RepairOrder::STAT_ACCEPT]) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <!-- /.info-box -->
            </div>
            <!-- ./col -->
        <?php } ?>
        <div class="col-md-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= isset($total['repair'][RepairOrder::STAT_DISPATCH]) ? $total['repair'][RepairOrder::STAT_DISPATCH] : 0 ?></h3>
                    <p>待修理</p>
                </div>
                <div class="icon">
                    <i class="fa fa-wrench"></i>
                </div>
                <a href="<?= \yii\helpers\Url::toRoute(['work/repair-work', 'RepairOrderSearch[stat]' => RepairOrder::STAT_DISPATCH]) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            <!-- /.info-box -->
        </div>
        <!-- ./col -->

    </div>
<?php } ?>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- TO DO List -->

        <div class="box box-primary repairorder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-wrench"></i> 报修广场</h3>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                Pjax::begin();
                ?>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <?=
                GridView::widget([
                    'dataProvider' => $repairorder,
                    'layout' => "<div class=table-responsive>{items}</div>\n{pager}",
                    'pager' => [
                        'options' => ['class' => 'pagination pagination-sm inline']
                    ],
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'columns' => [
                        ['attribute' => 'serial',
                            'value' =>
                            function($model) {
                                return Html::a($model->serial, '#', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'view',
                                ]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'name',
                            'value' =>
                            function($model) {

                                return mb_substr($model->name, 0, 1, 'utf-8') . mb_substr(mb_ereg_replace('\w', '*', $model->name), 1);
                            },
                        ],
                        [
                            'attribute' => 'repair_type',
                            'value' =>
                            function($model) {
                                return $model->repair_type ? $model->type->v : $model->repair_type;
                            },
                        ],
                        [
                            'attribute' => 'repair_area',
                            'value' =>
                            function($model) {
                                return $model->repair_area ? dms\models\Forum::get_forum_allname($model->repair_area) : $model->repair_area;
                            },
                        ],
                        //'content',
                        ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"]],
                        [
                            'attribute' => 'stat',
                            'value' =>
                            function($model) {
                                return $model->Stat;   //主要通过此种方式实现
                            },
                        ],
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>

            </div>
            <!-- /.box-body -->

        </div>

        <div class="box box-primary pickuporder">
            <div class="box-header ui-sortable-handle" style="cursor: move;">


                <h3 class="box-title"><i class="fa fa-suitcase"></i> 拾物招领</h3>


            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <?php
                Pjax::begin();
                ?>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <?=
                GridView::widget([
                    'dataProvider' => $pickup,
                    'layout' => "<div class=table-responsive>{items}</div>\n{pager}",
                    'pager' => [
                        'options' => ['class' => 'pagination pagination-sm inline']
                    ],
                    'tableOptions' => ['class' => 'table table-bordered table-hover'],
                    'rowOptions' => function($model) {
                        return ['class' => $model->type == \dms\models\Pickup::TYPE_PICK ? 'success' : 'danger'];
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
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
                        'name',
                        'tel',
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>

            </div>
            <!-- /.box-body -->


        </div>

        <!-- /.box -->
        <!--        <div class="box box-primary">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">


                        <h3 class="box-title"><i class="fa fa-bullhorn"></i> 公告</h3>

                        <div class="box-tools pull-right">
                            <ul class="pagination pagination-sm inline">
                                <li><a href="#">«</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">»</a></li>
                            </ul>
                        </div>
                    </div>
                     /.box-header
                    <div class="box-body">
                         See dist/js/pages/dashboard.js to activate the todoList plugin
                        <ul class="todo-list">
                            <li>
                                <span class="text">Design a nice theme</span>
                                 Emphasis label
                                <small class="text-right"><i class="fa fa-clock-o"></i> 2 mins</small>
                            </li>
                            <li>
                                <span class="text">Make the theme responsive</span>
                                <small class="pull-right"><i class="fa fa-clock-o"></i> 4 hours</small>
                            </li>
                            <li>
                                <span class="text">Let theme shine like a star</span>
                                <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                            </li>
                            <li>
                                <span class="text">Let theme shine like a star</span>
                                <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                            </li>
                            <li>
                                <span class="text">Check your messages and notifications</span>
                                <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                            </li>
                            <li>
                                <span class="text">Let theme shine like a star</span>
                                <small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>

                            </li>
                        </ul>
                    </div>
                     /.box-body

                </div>-->



    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">
        <?php Pjax::begin(); ?>
        <div class="nav-tabs-custom" style="cursor: move;">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li><a href="#enternews" data-toggle="tab">娱乐</a></li>
                <li><a href="#mil" data-toggle="tab">军事</a></li>

                <li class="active"><a href="#internet" data-toggle="tab">互联网</a></li>
                <li class="pull-left header"><i class="fa fa-newspaper-o"></i> 新闻</li>
            </ul>
            <div class="tab-content">
                <div class="baidunews tab-pane" id="enternews" >
                    <script language="JavaScript" type="text/JavaScript" src="http://news.baidu.com/n?cmd=1&class=enternews&pn=1&tn=newsbrofcu"></script>
                </div>
                <div class="baidunews tab-pane" id="mil" >
                    <script language="JavaScript" type="text/JavaScript" src="http://news.baidu.com/n?cmd=1&class=mil&pn=1&tn=newsbrofcu"></script>
                </div>

                <div class="baidunews tab-pane active" id="internet" >
                    <script language="JavaScript" type="text/JavaScript" src="http://news.baidu.com/n?cmd=1&class=internet&pn=1&tn=newsbrofcu"></script>
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
    $('.repairorder').on('click', '.view', function () {
        $('.modal-title').html('报修详情');
        $('.modal-body').html('');

        $.get('<?= Url::toRoute('repair-view') ?>', {id: $(this).closest('tr').data('key')},
                function (data) {
                    $('.modal-body').html(data);
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['index'], \yii\web\View::POS_END); ?>
