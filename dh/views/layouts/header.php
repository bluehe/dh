<?php

use yii\bootstrap\Nav;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header>


    <div class="main-header">
        <div class="container">
            <div class="pull-left">
                <a href="javascript:void(0);"><i class="fa fa-exchange"></i> 更换皮肤</a>
                <a href="javascript:void(0);"><i class="fa fa-refresh"></i> 切换板式</a>
            </div>


            <!--右侧功能块-->
            <div class="pull-right">
                <a href="javascript:void(0);"><i class="fa fa-sign-in"></i> 登录</a>
                <a href="/User/register"><i class="fa fa-pencil"></i> 注册</a>
            </div>
        </div>
    </div>
    <div class="nav-header">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <img src="<?= $directoryAsset ?>/image/logo.png" alt="导航" class="img-responsive"/>
                </a>
            </div>
            <?php
            $menuItems = [
                ['label' => '网址大全', 'url' => ['/site/index']],
                ['label' => '我的导航', 'url' => ['/']],
                ['label' => '行业导航', 'url' => ['/']],
                ['label' => '热门推荐', 'url' => ['/']],
            ];
            ?>
            <?php
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left hidden-xs'],
                'items' => $menuItems,
            ]);
            ?>

            <div class="search pull-right">
                <form action="https://www.baidu.com/s" method="get" target="_blank" accept-charset="utf-8" id="searchform">
                    <span><img src="<?= $directoryAsset ?>/image/baidu.png"></span>
                    <input type="text" name="wd" placeholder="请输入您要搜索的内容..." autocomplete="off">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>

</header>
