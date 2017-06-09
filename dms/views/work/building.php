<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use dms\models\Room;
use dms\models\Bed;
use dms\models\CheckOrder;
use dms\models\Student;

$this->title = '楼苑概况';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/repair-work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class="col-md-11">
        <div class="box box-success">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
                <i class="fa fa-bar-chart"></i>

                <h3 class="box-title">图例</h3>

                <div class="box-tools pull-right" data-toggle="tooltip">
                    <div class="btn-group" data-toggle="btn-toggle">
                        <button type="button" class="btn btn-default btn-sm toggle_tl active" data-toggle="room">房间</button>
                        <button type="button" class="btn btn-default btn-sm toggle_tl" data-toggle="stat">床位</button>
                        <button type="button" class="btn btn-default btn-sm toggle_tl" data-toggle="gender">学生性别</button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="target_tl target_room">
                    <div class="col-md-1 col-xs-3 text-center label-danger">关闭</div>
                    <div class="col-md-1 col-xs-3 text-center label-default">启用</div>
                    <div class="col-md-1 col-xs-3 text-center label-warning">住满</div>
                    <div class="col-md-1 col-xs-3 text-center label-info">未住满</div>
                </div>
                <div class="target_tl target_stat hide">
                    <div class="col-md-1 col-xs-3 text-center bg-red">关闭</div>
                    <div class="col-md-1 col-xs-3 text-center bg-green">预订</div>
                    <div class="col-md-1 col-xs-3 text-center bg-blue">住宿</div>
                    <div class="col-md-1 col-xs-3 text-center bg-gray">空</div>
                </div>
                <div class="target_tl target_gender hide">
                    <div class="col-md-1 col-xs-1 text-center bg-aqua">男</div>
                    <div class="col-md-1 col-xs-1 text-center bg-fuchsia">女</div>
                    <div class="col-md-1 col-xs-1 text-center bg-yellow">未定义</div>
                    <div class="col-md-1 col-xs-1 text-center bg-gray">空</div>
                    <div class="col-md-1 col-xs-1 text-center bg-black">非学生</div>
                </div>
            </div>

        </div>

        <?php foreach ($forums as $k => $forum) { ?>
            <?php if (isset($total['broom_num'][$k])) { ?>
                <div class="box box-primary" id="forum_<?= $k ?>">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $forum['forum_name'] ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="收缩">
                                <i class="fa fa-minus"></i></button>
                        </div>
                        <div style="line-height: 24px;">
                            <?php if (isset($total['broom_open'][$k])) { ?>
                                <span class="label label-primary">可用房间数：<?= $total['broom_open'][$k] ?><?= isset($total['sroom_open'][$k]) ? '(' . $total['sroom_open'][$k] . ')' : '' ?></span>
                            <?php } ?>
                            <?php if (isset($total['bed_open'][$k])) { ?>
                                <span class="label label-success">可用床位：<?= isset($total['forum_check'][$k]) ? $total['forum_check'][$k] : 0 ?> / <?= $total['bed_open'][$k] ?></span>
                                <span class="label label-default"><i class="fa fa-bar-chart"></i>入住率：<?= Yii::$app->formatter->asPercent(isset($total['forum_check'][$k]) ? $total['forum_check'][$k] / $total['bed_open'][$k] : 0 / $total['bed_open'][$k], 2) ?></span>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="box-body">

                        <?php foreach ($forum['floor'] as $floor) { ?>
                            <!--一级楼苑房间-->
                            <div class="floor row">
                                <div class="col-md-1 col-xs-12 text-center clearspace"><?= $floor['floor_name'] ?></div>
                                <div class="col-md-11 col-xs-12">
                                    <?php foreach ($floor['broom'] as $bid => $broom) { ?>
                                        <?php if ($broom['sroom']) { ?>
                                            <!--套间-->
                                            <div class="col-md-<?= count($broom['sroom']) ?> col-xs-<?= count($broom['sroom']) * 3 ?> clearspace">
                                                <div class="broom row clearspace text-center ">
                                                    <h4 class="text_ellipsis col-xs-12 <?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$bid]) ? ($total['bed_num'][$bid] == $total['room_check'][$bid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>"><?= $broom['name'] ?></h4>
                                                    <?php foreach ($broom['sroom'] as $sid => $sroom) { ?>

                                                        <div class="clearspace text-center" style="float:left;width:<?= 100 / count($broom['sroom']) . '%' ?>">
                                                            <div class="sroom <?= $sroom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$sid]) ? ($total['bed_num'][$sid] == $total['room_check'][$sid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>">

                                                                <div>
                                                                    <h4 class="text_ellipsis"><?= $sroom['name'] ?></h4>
                                                                    <div class="bed_content">
                                                                        <i class="fa"><?= $sroom['stat'] == Room::STAT_OPEN ? (isset($total['bed_num'][$sid]) ? ((isset($total['room_check'][$sid]) ? $total['room_check'][$sid] : 0) . '/' . $total['bed_num'][$sid]) : '无') : $sroom['note'] ?></i>
                                                                        <?php if (isset($sroom['bed']) && $sroom['bed']) { ?>
                                                                            <?php foreach ($sroom['bed'] as $bed) { ?>
                                                                                <div data-stat="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_check'][$bed['id']]) ? ( $total['bed_check'][$bed['id']] == CheckOrder::STAT_CHECKIN ? 'bg-blue' : 'bg-green') : 'bg-gray') : 'bg-red' ?>" data-gender="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_student'][$bed['id']]['gender']) ? ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_MALE ? 'bg-aqua' : ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_FEMALE ? 'bg-fuchsia' : 'bg-yellow')) : (isset($total['bed_check'][$bed['id']]) ? 'bg-black' : 'bg-gray')) : 'bg-red' ?>" style="<?= count($sroom['bed']) > 5 ? 'height:50%;width:' . (100 / ceil(count($sroom['bed']) / 2)) . '%' : 'height:100%;width:' . (100 / count($sroom['bed'])) . '%' ?>" title="<?= $bed['note'] ?>" data-toggle="tooltip"><?= $bed['name'] ?></div>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <div style="border:none;float:none;line-height: 30px;font-size: 14px;">无</div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                        <?php } else { ?>
                                            <!--单间-->
                                            <div class="col-md-1 col-xs-3 clearspace text-center">
                                                <div class="sroom <?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$bid]) ? ($total['bed_num'][$bid] == $total['room_check'][$bid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>">
                                                    <h4 class="text_ellipsis"><?= $broom['name'] ?></h4>
                                                    <div class="bed_content">
                                                        <i class="fa"><?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['bed_num'][$bid]) ? ((isset($total['room_check'][$bid]) ? $total['room_check'][$bid] : 0) . '/' . $total['bed_num'][$bid]) : '无') : $broom['note'] ?></i>
                                                        <?php if (isset($broom['bed']) && $broom['bed']) { ?>
                                                            <?php foreach ($broom['bed'] as $bed) { ?>
                                                                <div data-stat="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_check'][$bed['id']]) ? ( $total['bed_check'][$bed['id']] == CheckOrder::STAT_CHECKIN ? 'bg-blue' : 'bg-green') : 'bg-gray') : 'bg-red' ?>" data-gender="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_student'][$bed['id']]['gender']) ? ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_MALE ? 'bg-aqua' : ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_FEMALE ? 'bg-fuchsia' : 'bg-yellow')) : (isset($total['bed_check'][$bed['id']]) ? 'bg-black' : 'bg-gray')) : 'bg-red' ?>" style="<?= count($broom['bed']) > 5 ? 'height:50%;width:' . (100 / ceil(count($broom['bed']) / 2)) . '%' : 'height:100%;width:' . (100 / count($broom['bed'])) . '%' ?>" title="<?= $bed['note'] ?>" data-toggle="tooltip"><?= $bed['name'] ?></div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div style="border:none;float:none;line-height: 30px;font-size: 14px;">无</div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php foreach ($forum['children'] as $index => $child) { ?>
                            <?php if (isset($total['broom_num'][$index])) { ?>

                                <div class="box" id="forum_<?= $index ?>">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?= $child['forum_name'] ?></h3>

                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="收缩">
                                                <i class="fa fa-minus"></i></button>
                                        </div>
                                        <div style="line-height: 24px;">
                                            <?php if (isset($total['broom_open'][$index])) { ?>
                                                <span class="label label-primary">可用房间数：<?= $total['broom_open'][$index] ?><?= isset($total['sroom_open'][$index]) ? '(' . $total['sroom_open'][$index] . ')' : '' ?></span>
                                            <?php } ?>
                                            <?php if (isset($total['bed_open'][$index])) { ?>
                                                <span class="label label-success">可用床位：<?= isset($total['forum_check'][$index]) ? $total['forum_check'][$index] : 0 ?> / <?= $total['bed_open'][$index] ?></span>
                                                <span class="label label-default"><i class="fa fa-bar-chart"></i>入住率：<?= Yii::$app->formatter->asPercent(isset($total['forum_check'][$index]) ? $total['forum_check'][$index] / $total['bed_open'][$index] : 0 / $total['bed_open'][$index], 2) ?></span>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <?php foreach ($child['floor'] as $floor) { ?>
                                            <!--二级楼苑房间-->
                                            <div class="floor row">
                                                <div class="col-md-1 col-xs-12 text-center clearspace"><?= $floor['floor_name'] ?></div>
                                                <div class="col-md-11 col-xs-12">
                                                    <?php foreach ($floor['broom'] as $bid => $broom) { ?>
                                                        <?php if ($broom['sroom']) { ?>
                                                            <!--套间-->
                                                            <div class="col-md-<?= count($broom['sroom']) ?> col-xs-<?= count($broom['sroom']) * 3 ?> clearspace">
                                                                <div class="broom row clearspace text-center ">
                                                                    <h4 class="text_ellipsis col-xs-12 <?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$bid]) ? ($total['bed_num'][$bid] == $total['room_check'][$bid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>"><?= $broom['name'] ?></h4>
                                                                    <?php foreach ($broom['sroom'] as $sid => $sroom) { ?>

                                                                        <div class="clearspace text-center" style="float:left;width:<?= 100 / count($broom['sroom']) . '%' ?>">
                                                                            <div class="sroom <?= $sroom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$sid]) ? ($total['bed_num'][$sid] == $total['room_check'][$sid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>">

                                                                                <div>
                                                                                    <h4 class="text_ellipsis"><?= $sroom['name'] ?></h4>
                                                                                    <div class="bed_content">
                                                                                        <i class="fa"><?= $sroom['stat'] == Room::STAT_OPEN ? (isset($total['bed_num'][$sid]) ? ((isset($total['room_check'][$sid]) ? $total['room_check'][$sid] : 0) . '/' . $total['bed_num'][$sid]) : '无') : $sroom['note'] ?></i>
                                                                                        <?php if (isset($sroom['bed']) && $sroom['bed']) { ?>
                                                                                            <?php foreach ($sroom['bed'] as $bed) { ?>
                                                                                                <div data-stat="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_check'][$bed['id']]) ? ( $total['bed_check'][$bed['id']] == CheckOrder::STAT_CHECKIN ? 'bg-blue' : 'bg-green') : 'bg-gray') : 'bg-red' ?>" data-gender="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_student'][$bed['id']]['gender']) ? ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_MALE ? 'bg-aqua' : ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_FEMALE ? 'bg-fuchsia' : 'bg-yellow')) : (isset($total['bed_check'][$bed['id']]) ? 'bg-black' : 'bg-gray')) : 'bg-red' ?>" style="<?= count($sroom['bed']) > 5 ? 'height:50%;width:' . (100 / ceil(count($sroom['bed']) / 2)) . '%' : 'height:100%;width:' . (100 / count($sroom['bed'])) . '%' ?>" title="<?= $bed['note'] ?>" data-toggle="tooltip"><?= $bed['name'] ?></div>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?>
                                                                                            <div style="border:none;float:none;line-height: 30px;font-size: 14px;">无</div>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>

                                                        <?php } else { ?>
                                                            <!--单间-->
                                                            <div class="col-md-1 col-xs-3 clearspace text-center">
                                                                <div class="sroom <?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['room_check'][$bid]) ? ($total['bed_num'][$bid] == $total['room_check'][$bid] ? 'label-warning' : 'label-info') : 'label-default') : 'label-danger' ?>">
                                                                    <h4 class="text_ellipsis"><?= $broom['name'] ?></h4>
                                                                    <div class="bed_content">
                                                                        <i class="fa"><?= $broom['stat'] == Room::STAT_OPEN ? (isset($total['bed_num'][$bid]) ? ((isset($total['room_check'][$bid]) ? $total['room_check'][$bid] : 0) . '/' . $total['bed_num'][$bid]) : '无') : $broom['note'] ?></i>
                                                                        <?php if (isset($broom['bed']) && $broom['bed']) { ?>
                                                                            <?php foreach ($broom['bed'] as $bed) { ?>
                                                                                <div data-stat="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_check'][$bed['id']]) ? ( $total['bed_check'][$bed['id']] == CheckOrder::STAT_CHECKIN ? 'bg-blue' : 'bg-green') : 'bg-gray') : 'bg-red' ?>" data-gender="<?= $bed['stat'] == Bed::STAT_OPEN ? (isset($total['bed_student'][$bed['id']]['gender']) ? ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_MALE ? 'bg-aqua' : ($total['bed_student'][$bed['id']]['gender'] == Student::GENDER_FEMALE ? 'bg-fuchsia' : 'bg-yellow')) : (isset($total['bed_check'][$bed['id']]) ? 'bg-black' : 'bg-gray')) : 'bg-red' ?>" style="<?= count($broom['bed']) > 5 ? 'height:50%;width:' . (100 / ceil(count($broom['bed']) / 2)) . '%' : 'height:100%;width:' . (100 / count($broom['bed'])) . '%' ?>" title="<?= $bed['note'] ?>" data-toggle="tooltip"><?= $bed['name'] ?></div>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <div style="border:none;float:none;line-height: 30px;font-size: 14px;">无</div>
                                                                        <?php } ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            <?php } ?>

                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="col-md-1 hidden-xs hidden-sm">

        <nav id="navbar-forum" class="nav-tabs-custom" data-spy="affix" data-offset-top="106" data-offset-bottom="86">
            <ul class="nav" role="tablist">
                <?php foreach ($forums as $k => $forum) { ?>
                    <?php if (isset($total['broom_num'][$k])) { ?>
                        <li>
                            <a href="#forum_<?= $k ?>"><b><?= $forum['forum_name'] ?></b></a>
                            <?php if ($forum['children']) { ?>
                                <ul class="nav" role="tablist">
                                    <?php foreach ($forum['children'] as $index => $child) { ?>
                                        <?php if (isset($total['broom_num'][$index])) { ?>
                                            <li><a href="#forum_<?= $index ?>"><?= $child['forum_name'] ?></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>

    </div>
</div>
<script>
<?php $this->beginBlock('index') ?>
    $('body').scrollspy({target: '#navbar-forum'});
    $('.toggle_tl').on('click', function () {
        var $to = $(this).attr('data-toggle');
        $('.target_tl').slice().addClass('hide');
        $('.target_' + $to).removeClass('hide');
        if ($to == 'room') {
            $('.bed_content>div').hide();
            $('.sroom i.fa').show();
        } else {
            $('.sroom i.fa').hide();
            $('.bed_content>div').show();
        }
        $('.bed_content>div').each(function () {
            var $c = $(this).attr('data-' + $to);
            $(this).removeClass().addClass($c);
        });
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['index'], \yii\web\View::POS_END); ?>