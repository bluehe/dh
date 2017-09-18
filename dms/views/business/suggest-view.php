<?php

//use yii\helpers\Html;
//use yii\widgets\DetailView;
use dms\models\Suggest;
use kartik\rating\StarRating;

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

            <?php if ($model->stat === Suggest::STAT_CLOSE) { ?>
                <!-- timeline item -->
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
            <?php if ($model->stat === Suggest::STAT_REPLY || $model->stat === Suggest::STAT_EVALUATE) { ?>
                <!-- timeline item -->
                <li>
                    <i class="fa bg-yellow">回复</i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->reply_at) ?></span>
                        <h3 class="timeline-header"><?= $model->r->username ?></h3>
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
            <?php if ($model->stat === Suggest::STAT_EVALUATE) { ?>
                <!-- timeline item -->
                <li>
                    <i class="fa bg-fuchsia">评价</i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->end_at) ?></span>
                        <h3 class="timeline-header"><?= $model->evaluate === Suggest::EVALUATE_USER ? $model->u->username : '系统' ?></h3>
                        <div class="timeline-body">
                            <dl class="dl-horizontal">
                                <dt><?= $model->getAttributeLabel('evaluate1') ?></dt><dd><?=
                                    StarRating::widget([
                                        'model' => $model,
                                        'attribute' => 'evaluate1',
                                        'pluginOptions' => ['disabled' => true, 'showClear' => false, 'size' => 'lx', 'starCaptions' => Suggest::$List['evaluate']]
                                    ]);
                                    ?></dd>

                                <dt>评价详情</dt><dd><?= $model->note ? $model->note : '无' ?></dd>
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
