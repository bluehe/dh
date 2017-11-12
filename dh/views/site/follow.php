<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use dh\components\Tab;
use dh\models\User;
use dh\models\UserAtten;
use yii\widgets\LinkPager;

$this->title = '关注列表';
?>
<div class="container">

    <div class="row content">
        <section class="col-lg-3">
            <?=
            $this->render('left.php')
            ?>

        </section>
        <section class="col-lg-9">

            <div class="row">

                <div class="col-lg-12 follow">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class=" active"><a href="#follow">关注了</a></li>
                        <li role="presentation"><a href="#fans">关注者</a></li>

                    </ul>
                    <?php Pjax::begin(); ?>
                    <div class="follow-user list-group">
                        <?php foreach ($model as $user_id) { ?>
                            <div class="list-group-item">
                                <div class="ContentItem-image">
                                    <?= Html::img(User::get_avatar($user_id), ['class' => 'img-thumbnail', 'width' => 100, 'height' => 100]) ?>
                                </div>
                                <div class="ContentItem-head">
                                    <div class="ContentItem-title"><?= Html::a(User::get_nickname($user_id), ['site/people', 'user_id' => $user_id], ['class' => 'txt1']) ?></div>
                                    <div class="ContentItem-meta"><div><div class="RichText">前腾讯京东战略分析师，电商天使投资人</div><div class="ContentItem-status"><span class="ContentItem-statusItem">114 回答</span><span class="ContentItem-statusItem">265 文章</span><span class="ContentItem-statusItem">16342 关注者</span></div></div></div></div>
                                <div class="ContentItem-extra"><button class="Button FollowButton Button--primary Button--blue" type="button"></button></div>
                            </div>
                        <?php } ?>


                        <div class="text-center"><?= LinkPager::widget(['pagination' => $pages]); ?></div>
                    </div>

                    <?php Pjax::end(); ?>
                </div>

            </div>

        </section>
    </div>
</div>
<script>
<?php $this->beginBlock('follow') ?>

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['follow'], \yii\web\View::POS_END); ?>