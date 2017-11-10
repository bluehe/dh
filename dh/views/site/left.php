<?php

use dh\components\Tab;
use dh\models\Website;
use dh\models\User;
use dh\models\UserAtten;
use dh\models\UserSign;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
if (Yii::$app->controller->action->id == 'people' || !Yii::$app->user->isGuest) {
    if (Yii::$app->controller->action->id == 'people') {
        $user_id = Yii::$app->request->get('id');
    } else {
        $user_id = Yii::$app->user->identity->id;
    }
    ?>
    <div class="user-head">
        <div class="person_info">
            <div class="cover"></div>
            <div class="innerwrap">
                <div class="profile">
                    <div class="headpic">
                        <?= Html::img(User::get_avatar($user_id), ['class' => 'img-thumbnail', 'width' => 70, 'height' => 70]) ?>
                        <?= Yii::$app->controller->action->id == 'people' ? '' : Html::a('<div class="mask"><div class="Mask-mask Mask-mask--black UserAvatarEditor-maskInner"></div><div class="Mask-content"><i class="glyphicon glyphicon-camera"></i><div class="UserAvatarEditor-maskInnerText">修改头像</div></div></div>', ['account/thumb']) ?>

                    </div>
                    <div class="nameBox">
                        <div class="title">
                            <span class="name txt1"><?= User::get_nickname($user_id) ?></span>
                        </div>
                        <span><a title="等级" href="#"><span class="badge">Lv.32</span></a> <a title="会员" href="#"><i class="W_icon icon_member"></i></a></span>
                        <div class="profile-contentFooter">
                            <div class="profileHeader-buttons">
                                <?php
                                if (Yii::$app->controller->action->id == 'people') {
                                    echo Yii::$app->user->isGuest || !UserAtten::is_atten(Yii::$app->user->identity->id, $user_id) ? Html::tag('button', '<i class="fa fa-plus"></i> 关注', ['class' => 'btn btn-xs btn-primary']) : Html::tag('button', '已关注', ['class' => 'btn btn-xs btn-default']);
                                    // echo Html::a('<i class="fa fa-commenting-o"></i> 私信', ['#'], ['class' => 'btn btn-xs btn-default']);
                                } else {
                                    echo UserSign::exist_sign(Yii::$app->user->identity->id) ? Html::tag('button', '已签到', ['class' => 'btn btn-xs btn-default', 'disabled' => 'disabled']) : Html::tag('button', '<i class="fa fa-edit"></i>签到', ['class' => 'btn btn-xs btn-primary user-sign']);
                                    //echo Html::a('编辑', ['account/index'], ['class' => 'btn btn-xs btn-default btn-edit']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="user_atten">
                    <li class="line1 col-lg-4 col-xs-4"><a href="#" class="txt1"><strong node-type="follow"><?= UserAtten::get_num($user_id) ?></strong><span class="txt2">关注了</span></a></li>
                    <li class="line1 col-lg-4 col-xs-4"><a href="#" class="txt1"><strong node-type="fans"><?= UserAtten::get_num($user_id, 'fans') ?></strong><span class="txt2">关注者</span></a></li>
                    <li class="line1 col-lg-4 col-xs-4"><?= Html::a('<strong>' . Website::get_website_num($user_id, '', Website::STAT_OPEN) . '</strong><span class="txt2">网址</span>', ['site/people', 'id' => $user_id], ['class' => 'txt1']) ?></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="mk hidden-xs<?= Yii::$app->controller->action->id == 'people' || !Yii::$app->user->isGuest ? ' user' : '' ?>">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 col-md-4 active"><a href="#useradd" aria-controls="useradd" role="tab" data-toggle="tab">新用户</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userfans" aria-controls="userfans" role="tab" data-toggle="tab">关注排行</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userlevel" aria-controls="userclick" role="tab" data-toggle="tab">等级排行</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="userscroll">
        <div role="tabpanel" class="tab-pane box active" id="useradd">
            <div class="list-group list">
                <?=
                Tab::widget(['items' => User::get_tab_useradd(20)]
                )
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userfans">
            <div class="list-group">
                <?=
                Tab::widget(['items' => UserAtten::get_tab_userfans(10)]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userlevel">
            <div class="list-group">
                <?=
                Tab::widget(['items' => User::get_tab_userlevel(10)]
                )
                ?>

            </div>
        </div>

    </div>

</div>

<div class="mk hidden-xs">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 col-md-4 active"><a href="#addlist" aria-controls="addlist" role="tab" data-toggle="tab">网址动态</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#addorder" aria-controls="addorder" role="tab" data-toggle="tab">添加排行</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#clickorder" aria-controls="clickorder" role="tab" data-toggle="tab">点击排行</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="cxscroll">
        <div role="tabpanel" class="tab-pane box active" id="addlist">
            <div class="list-group list">
                <?=
                Tab::widget(['items' => Website::get_tab_addlist(20)]
                )
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="addorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_addorder(10)]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="clickorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_clickorder(10)]
                )
                ?>

            </div>
        </div>

    </div>

</div>
<?php dh\assets\AppAsset::addScript($this, Yii::$app->assetManager->getPublishedUrl('dh/web') . '/js/jquery.cxscroll.min.js') ?>
<script>
<?php $this->beginBlock('left') ?>
    $(function () {
        $("#userscroll").cxScroll({direction: "bottom", speed: 1000, time: 2000});
        $("#cxscroll").cxScroll({direction: "bottom", speed: 1000, time: 2000});
    });
    //用户签到
    $('.user-sign').on('click', function () {
        $.getJSON("<?= Url::toRoute('ajax/user-sign') ?>", function (data) {
            if (data.stat === 'success') {
                $('.user-sign').addClass('btn-default').attr('disabled', 'disabled').html('已签到').removeClass('btn-primary user-sign');
                my_alert('success', data.msg, 3000);
            } else if (data.stat === 'fail') {
                my_alert('danger', data.msg, 3000);
            }
        });

    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['left'], \yii\web\View::POS_END); ?>
