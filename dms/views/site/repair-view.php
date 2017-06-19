<?php

//use yii\helpers\Html;
//use yii\widgets\DetailView;
use dms\models\RepairOrder;
use kartik\rating\StarRating;

mb_regex_encoding("UTF-8");

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
                <i class="fa bg-teal">报修</i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->created_at) ?></h3>

                    <div class="timeline-body">
                        <dl class="dl-horizontal">
                            <dt><?= $model->getAttributeLabel('repair_type') ?></dt><dd><?= $model->repair_type ? $model->type->v : $model->repair_type ?></dd>
                            <dt><?= $model->getAttributeLabel('repair_area') ?></dt><dd><?= $model->repair_area ? dms\models\Forum::get_forum_allname($model->repair_area) : $model->repair_area ?></dd>
                            <dt><?= $model->getAttributeLabel('address') ?></dt><dd><?= $model->address ?></dd>
                            <dt><?= $model->getAttributeLabel('content') ?></dt><dd><?= $model->content ?></dd>
                            <dt><?= $model->getAttributeLabel('name') ?></dt><dd><?= mb_substr($model->name, 0, 1, 'utf-8') . mb_substr(mb_ereg_replace('\w', '*', $model->name), 1) ?></dd>
                            <dt><?= $model->getAttributeLabel('tel') ?></dt><dd><?= substr_replace($model->tel, '****', 3, 4) ?></dd>



                        </dl>
                    </div>

                </div>
            </li>
            <!-- END timeline item -->
            <?php
            if ($model->stat === RepairOrder::STAT_CLOSE) {
                ?><!-- timeline item -->
                <li>
                    <i class="fa bg-red">取消</i>

                    <div class="timeline-item">

                        <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->end_at) ?></h3>
                    </div>
                </li>
                <!-- END timeline item -->
                <?php
            } elseif ($model->stat === RepairOrder::STAT_NO_ACCEPT) {
                ?><!-- timeline item -->
                <li>
                    <i class="fa bg-red">未受理</i>

                    <div class="timeline-item">
                        <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->end_at) ?></h3>
                        <div class="timeline-body">
                            <dl class="dl-horizontal">
                                <dt><?= $model->getAttributeLabel('note') ?></dt><dd><?= $model->note ?></dd>
                            </dl>
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                <?php
            } elseif ($model->stat >= RepairOrder::STAT_ACCEPT) {
                ?><!-- timeline item -->
                <li>
                    <i class="fa bg-blue">受理</i>

                    <div class="timeline-item">

                        <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->accept_at) ?></h3>
                    </div>
                </li>
                <!-- END timeline item -->
                <?php if ($model->stat >= RepairOrder::STAT_DISPATCH) {
                    ?><!-- timeline item -->
                    <li>
                        <i class="fa bg-yellow">派工</i>

                        <div class="timeline-item">

                            <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->dispatch_at) ?></h3>
                            <div class="timeline-body">
                                <dl class="dl-horizontal">
                                    <dt><?= $model->getAttributeLabel('worker_id') ?></dt><dd><?= $model->worker_id ? mb_substr($model->worker->name, 0, 1, 'utf-8') . mb_substr(mb_ereg_replace('\w', '*', $model->worker->name), 1) : $model->worker_id ?></dd>
                                    <dt>联系电话</dt><dd><?= $model->worker_id ? substr_replace($model->worker->tel, '****', 3, 4) : $model->worker_id ?></dd>
                                </dl>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->

                    <?php
                }
                if ($model->stat >= RepairOrder::STAT_REPAIRED) {
                    ?><!-- timeline item -->
                    <li>
                        <i class="fa bg-green">维修</i>

                        <div class="timeline-item">

                            <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->repair_at) ?></h3>

                        </div>
                    </li>
                    <!-- END timeline item -->

                    <?php
                }
                if ($model->stat === RepairOrder::STAT_EVALUATE) {
                    ?><!-- timeline item -->
                    <li>
                        <i class="fa bg-fuchsia">评价</i>

                        <div class="timeline-item">

                            <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <?= date('Y-m-d H:i:s', $model->end_at) ?></h3>
                            <div class="timeline-body">
                                <dl class="dl-horizontal">
                                    <dt><?= $model->getAttributeLabel('evaluate1') ?></dt><dd><?=
                                        StarRating::widget([
                                            'model' => $model,
                                            'attribute' => 'evaluate1',
                                            'pluginOptions' => ['disabled' => true, 'showClear' => false, 'size' => 'lx', 'starCaptions' => RepairOrder::$List['evaluate']]
                                        ]);
                                        ?></dd>
                                    <dt><?= $model->getAttributeLabel('evaluate2') ?></dt><dd><?=
                                        StarRating::widget([
                                            'model' => $model,
                                            'attribute' => 'evaluate2',
                                            'pluginOptions' => ['disabled' => true, 'showClear' => false, 'size' => 'lx', 'starCaptions' => RepairOrder::$List['evaluate']]
                                        ]);
                                        ?></dd>
                                    <dt><?= $model->getAttributeLabel('evaluate3') ?></dt><dd><?=
                                        StarRating::widget([
                                            'model' => $model,
                                            'attribute' => 'evaluate3',
                                            'pluginOptions' => ['disabled' => true, 'showClear' => false, 'size' => 'lx', 'starCaptions' => RepairOrder::$List['evaluate']]
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
            }
            ?>
            <li>
                <i class="fa fa-clock-o bg-gray"></i>
            </li>
        </ul>
    </div>
    <!-- /.col -->
</div>
