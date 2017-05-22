<?php
/* @var $this yii\web\View */

use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use dms\models\RepairOrder;

$this->title = '首页';
?>
<!-- Small boxes (Stat box) -->
<?php if (Yii::$app->user->can('楼苑设置')) { ?>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
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
        <div class="col-lg-3 col-xs-6">
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
        <div class="col-lg-3  col-xs-6">
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
        <div class="col-lg-3 col-xs-6">
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
        <div class="col-lg-3 col-xs-6">
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
            <div class="col-lg-3 col-xs-6">
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
            <div class="col-lg-3 col-xs-6">
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
        <div class="col-lg-3 col-xs-6">
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
        <?php Pjax::begin(); ?>

        <?php Pjax::end(); ?>
        <!-- /.box -->

    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">

        <!-- Custom tabs (Charts with tabs)-->

        <!-- /.nav-tabs-custom -->

    </section>
    <!-- right col -->
</div>
<!-- /.row (main row) -->
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
