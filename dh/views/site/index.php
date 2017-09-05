<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = '首页';
?>
<div class="container">

    <div class="row content">
        <section class="col-lg-3 hidden-xs">
            <?=
            $this->render('left.php')
            ?>

        </section>
        <section class="col-lg-9">
            <div class="row">
                <?php Pjax::begin(); ?>
                <div class="website plate-<?= Yii::$app->params['plate'] ?> col-lg-12">
                    <?php foreach ($cates as $cate) { ?>
                        <div class="category">
                            <div class="website-header">
                                <b><?= $cate['title'] ?></b>
                                <span class="header-icon pull-right"><i class="fa fa-star-o" title="收藏分类"></i></span>
                            </div>
                            <div class="website-content list-group">

                                <?php foreach ($cate['website'] as $website) { ?>
                                    <div class="list-group-item">
                                        <?= Html::img(['api/getfav', 'url' => $website['url']]) ?>
                                        <a href="<?= $website['url'] ?>" title="<?= $website['title'] ?>"><?= $website['title'] ?></a>
                                        <div class="content-icon index-icon pull-right" >
                                            <i class="fa fa-heart-o" title="收藏网址"></i>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="col-lg-12 text-center">
                    <?= LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 5, 'prevPageLabel' => '上一页', 'nextPageLabel' => '下一页']); ?>
                </div>
                <?php Pjax::end(); ?>
            </div>
        </section>
    </div>
</div>
