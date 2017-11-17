<?php

use dh\models\Suggest;
use dh\models\User;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row repaire-view">
    <div class="col-md-12">
        <!-- The time line -->
        <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-blue" style="margin-left: 12px">建议反馈</span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa bg-teal">提交</i>
                <div class="timeline-item">

                    <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->created_at) ?></span>
                    <h3 class="timeline-header"><?= User::get_nickname($model->uid) ?></h3>

                    <div class="timeline-body">
                        <dl class="dl-horizontal">

                            <dt><?= $model->getAttributeLabel('content') ?></dt><dd><?= $model->content ?></dd>

                        </dl>
                    </div>

                </div>
            </li>
            <!-- END timeline item -->

            <?php if ($model->stat === Suggest::STAT_CLOSE) { ?>
                <!-- timeline item -->
                <li>
                    <i class="fa bg-red">取消</i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->updated_at) ?></span>
                        <h3 class="timeline-header"><?= User::get_nickname($model->uid) ?></h3>
                    </div>
                </li>
                <!-- END timeline item -->
                <?php
            }
            ?>
            <?php if ($model->stat === Suggest::STAT_REPLY) { ?>
                <!-- timeline item -->
                <li>
                    <i class="fa bg-yellow">回复</i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->updated_at) ?></span>
                        <h3 class="timeline-header"><?= User::get_nickname($model->reply_uid) ?></h3>
                        <div class="timeline-body">
                            <dl class="dl-horizontal">
                                <dt><?= $model->getAttributeLabel('reply_content') ?></dt><dd><?= $model->reply_content ?></dd>

                            </dl>
                        </div>
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
