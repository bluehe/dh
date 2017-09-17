<?php

//use yii\helpers\Html;
//use yii\widgets\DetailView;
use dms\models\Suggest;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row repaire-view">
    <div class="col-md-12">
        <!-- The time line -->
        <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-blue"><?= $model->serial ?></span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa bg-teal">提交</i>
                <div class="timeline-item">

                    <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->created_at) ?></span>
                    <h3 class="timeline-header"><?= $model->u->username ?></h3>

                    <div class="timeline-body">
                        <dl class="dl-horizontal">
                            <dt><?= $model->getAttributeLabel('type') ?></dt><dd><?= $model->Type ?></dd>
                            <dt><?= $model->getAttributeLabel('content') ?></dt><dd><?= $model->content ?></dd>
                            <dt><?= $model->getAttributeLabel('name') ?></dt><dd><?= $model->name ?></dd>
                            <dt><?= $model->getAttributeLabel('tel') ?></dt><dd><?= $model->tel ?></dd>
                        </dl>
                    </div>

                </div>
            </li>
            <!-- END timeline item -->
            <?php if ($model->stat === Suggest::STAT_CLOSE) { ?><!-- timeline item -->
                <li>
                    <i class="fa bg-red">取消</i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->end_at) ?></span>
                        <h3 class="timeline-header"><?= $model->u->username ?></h3>
                    </div>
                </li>
                <!-- END timeline item -->
                <?php
            }
            ?>
            <li>
                <i class="fa fa-clock-o bg-gray"></i>
            </li>
        </ul>
    </div>
    <!-- /.col -->
</div>
