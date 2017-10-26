<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">' . Yii::$app->params['shortname'] . '</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">切换导航</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?><?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : '/image/user.png' ?>" class="user-image" alt="用户头像"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                    </a>

                    <div class="myhome dropdown-menu">
                        <?= Html::a('<i class="fa fa-home"></i><span>个人中心</span>', ['/user/index']) ?>
                        <?= Html::a('<i class="fa fa-sign-out"></i><span>退出</span>', ['/site/logout'], ['data-method' => 'post']) ?>
                    </div>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <!--                <li>
                                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                                </li>-->
            </ul>
        </div>
    </nav>
</header>
<script>
<?php $this->beginBlock('baidu') ?>
    (function () {
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        } else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['baidu'], \yii\web\View::POS_END); ?>