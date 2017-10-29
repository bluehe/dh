<?php

use dh\components\Tab;
use dh\models\Website;
use yii\helpers\Html;
?>
<div class="user-head">
    <?php if (Yii::$app->controller->action->id == 'people') {

    } elseif (Yii::$app->user->isGuest) {

    } else {
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
                    <li class="line1 col-lg-4 col-xs-4"><a href="" class="txt1"><strong node-type="follow">1</strong><span class="txt2">关注</span></a></li>
                    <li class="line1 col-lg-4 col-xs-4"><a href="" class="txt1"><strong node-type="fans">30</strong><span class="txt2">粉丝</span></a></li>
                    <li class="line1 col-lg-4 col-xs-4"><a href="" class="txt1"><strong node-type="weibo">0</strong><span class="txt2">网址</span></a></li>
                </ul>
            </div>
        </div>
<?php } ?>
</div>
<div class="mk user hidden-xs">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="col-lg-4 col-md-4 active"><a href="#useradd" aria-controls="addlist" role="tab" data-toggle="tab">新用户</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userorder" aria-controls="addorder" role="tab" data-toggle="tab">添加排行</a></li>
        <li role="presentation"  class="col-lg-4 col-md-4"><a href="#userclick" aria-controls="clickorder" role="tab" data-toggle="tab">点击排行</a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="userscroll">
        <div role="tabpanel" class="tab-pane box active" id="useradd">
            <div class="list-group list">
                <?=
                Tab::widget(['items' => Website::get_tab_adduser()]
                )
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_addorder()]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="userclick">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_clickorder()]
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
                Tab::widget(['items' => Website::get_tab_addlist()]
                )
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="addorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_addorder()]
                )
                ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="clickorder">
            <div class="list-group">
                <?=
                Tab::widget(['items' => Website::get_tab_clickorder()]
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
