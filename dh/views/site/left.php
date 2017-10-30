<?php

use dh\components\Tab;
use dh\models\Website;
use dh\models\User;
use dh\models\UserAtten;
use yii\helpers\Html;
?>
<div class="user-head">
    <?php
    if (Yii::$app->controller->action->id == 'people') {
        //查看他人
    } elseif (Yii::$app->user->isGuest) {
        //未登录
    } else {
        //自己登陆
        ?>
        <div class="person_info">
            <div class="cover">
                <div class="headpic"><?= Html::a(Html::img(Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : '/image/user.png', ['class' => 'img-circle', 'width' => 60, 'height' => 60]), ['user/index']) ?></div>
            </div>
            <div class="innerwrap">
                <div class="nameBox">
    <?= Html::a(Yii::$app->user->identity->username, ['user/index'], ['title' => Yii::$app->user->identity->username, 'class' => 'name txt1']) ?>
                    <a title="会员" href="#"><i class="W_icon icon_member_dis"></i></a>
                    <a title="等级" href="#"><span class="badge">Lv.0</span></a>
                </div>
                <ul class="user_atten">
                        <li class="line1 col-lg-4 col-xs-4"><a href="" class="txt1"><strong node-type="follow"><?= UserAtten::get_num(Yii::$app->user->identity->id) ?></strong><span class="txt2">关注</span></a></li>
                            <li class="line1 col-lg-4 col-xs-4"><a href="" class="txt1"><strong node-type="fans"><?= UserAtten::get_num(Yii::$app->user->identity->id, 'fans') ?></strong><span class="txt2">粉丝</span></a></li>
                                <li class="line1 col-lg-4 col-xs-4"><?= Html::a('<strong>' . Website::get_website_num(Yii::$app->user->identity->id, '', Website::STAT_OPEN) . '</strong><span class="txt2">网址</span>', ['site/user'], ['class' => 'txt1']) ?></li>
                    </ul>
            </div>
        </div>
<?php } ?>
</div>
<div class="mk user hidden-xs">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 col-md-4 active"><a href="#useradd" aria-controls="useradd" role="tab" data-toggle="tab">新用户</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userfans" aria-controls="userfans" role="tab" data-toggle="tab">粉丝排行</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userclick" aria-controls="userclick" role="tab" data-toggle="tab">点击排行</a></li>

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
                Tab::widget(['items' => UserAtten::get_tab_userfans(6)]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userclick">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_clickorder(6)]
                )
                ?>

            </div>
        </div>

    </div>

</div>

<div class="mk hidden-xs">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 col-md-4 active"><a href="#addlist" aria-controls="addlist" role="tab" data-toggle="tab">添加动态</a></li>
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
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['left'], \yii\web\View::POS_END); ?>
