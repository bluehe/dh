<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use dh\models\Recommend;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header>
    <div class="main-header">
        <div class="container">

            <div class="pull-left">
                <a href="javascript:void(0);" class="change-skin" data-skin="<?= Yii::$app->params['skin'] ?>"><i class="fa fa-exchange"></i> 更换皮肤</a>
                <a href="javascript:void(0);" class="change-plate" data-plate="<?= Yii::$app->params['plate'] ?>"><i class="fa fa-refresh"></i> 切换板式</a>
            </div>
            <!--右侧功能块-->
            <div class="pull-right">

                <?php if (Yii::$app->user->isGuest) { ?>
                    <?=
                    Html::a('<i class="fa fa-sign-in"></i> 登录', ['/site/login'])
                    ?>
                    <?=
                    Html::a('<i class="fa fa-pencil"></i> 注册', ['/site/signup'])
                    ?>
                <?php } else { ?>
                    <div class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : '/image/user.png' ?>" class="user-image" alt="用户头像" />
                            <span><?= Yii::$app->user->identity->username ?></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">


                                <p>
                                    <?= Yii::$app->user->identity->username ?>
                                    <small>注册时间 <?= date('Y-m-d', Yii::$app->user->identity->created_at) ?></small>
                                </p>
                            </li>

                            <li class="user-footer">
                                <!--                            <div class="pull-left">
                                                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                                            </div>-->
                                <div class="text-center">
                                    <?=
                                    Html::a('退 出', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat'])
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </div>

                <?php } ?>


            </div>
            <?php
            $menuItems = [
                ['label' => '网址大全', 'url' => ['site/index']],
                ['label' => '我的网址', 'url' => ['site/user']],
            ];
            ?>
            <nav class="navbar navbar-inverse visible-xs navbar-fixed-top">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">导航切换</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <?php
                        echo Nav::widget([
                            'options' => ['class' => 'navbar-nav nav'],
                            'items' => $menuItems,
                        ]);
                        ?>


                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
        </div>
    </div>
    <div class="nav-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-xs-12 logo">
                    <a href="<?= Yii::$app->homeUrl ?>">
                        <img src="<?= $directoryAsset ?>/image/logo.png" alt="<?= Yii::$app->name ?>" class="img-responsive center-block"/>
                    </a>
                </div>

                <div class="col-lg-4 col-lg-push-6 col-xs-12">
                    <form class="search" action="https://www.baidu.com/s" method="get" target="_blank" accept-charset="utf-8" id="searchform">
                        <span><img src="<?= $directoryAsset ?>/image/baidu.png"></span>
                        <input type="text" name="wd" placeholder="请输入您要搜索的内容..." autocomplete="off">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav col-lg-6 col-lg-pull-4 hidden-xs'],
                    'items' => $menuItems,
                ]);
                ?>


            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <?= Alert::widget() ?>

        <?php
        $recommend = Recommend::get_recommend(Recommend::STAT_OPEN, 12);
        if (count($recommend) > 0) {
            ?>

            <div class="container recommend">
                <?php foreach ($recommend as $r) { ?>
                    <div class="col-lg-1 col-xs-2 text-center">
                        <?= Html::a(Html::img('/data/recommend/' . $r['img'], ['alt' => $r['name'], 'class' => 'img-rounded img-responsive center-block']) . '<span class="hidden-xs">' . $r['name'] . '</span>', $r['url'], ['data-id' => $r['id']]) ?>

                    </div>
                    <?php
                }
                ?>
            </div>

        <?php } ?>
    </div>
</header>
<script>
<?php $this->beginBlock('header') ?>
    $('.change-skin').on('click', function () {
        var skin = $(this).data('skin');
        $.getJSON({
            url: '<?= Url::toRoute('ajax/change-skin') ?>',
            data: {'id': skin},
            success: function (data) {

                if (data.stat === 'success') {
                    $('body').removeClass('skin-' + skin).addClass('skin-' + data.skin);
                    $('.change-skin').data('skin', data.skin);
                }

            }
        });
    });
    $('.change-plate').on('click', function () {
        var plate = $(this).data('plate');
        $.getJSON({
            url: '<?= Url::toRoute('ajax/change-plate') ?>',
            data: {'id': plate},
            success: function (data) {

                if (data.stat === 'success') {
                    $(".website").removeClass('plate-' + plate).addClass('plate-' + data.plate);
                    $('.change-plate').data('plate', data.plate);
                }

            }
        });
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['header'], \yii\web\View::POS_END); ?>