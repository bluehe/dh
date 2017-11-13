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
                        <li role="presentation" <?= Yii::$app->request->get('type', 'follow') == 'follow' ? 'class=" active"' : '' ?>><?= Html::a('关注了', ['site/follow', 'id' => Yii::$app->request->get('id')]) ?></li>
                        <li role="presentation" <?= Yii::$app->request->get('type', 'follow') == 'follow' ? '' : 'class=" active"' ?>><?= Html::a('关注者', ['site/follow', 'id' => Yii::$app->request->get('id'), 'type' => 'fans']) ?></li>

                    </ul>
                    <?php Pjax::begin(); ?>
                    <div class="follow-user list-group">
                        <?php if (count($model) > 0) { ?>
                            <?php foreach ($model as $id) { ?>
                                <div class="list-group-item">
                                <div class="ContentItem-image">
                                            <?= Html::img(User::get_avatar($id), ['class' => 'img-thumbnail', 'width' => 100, 'height' => 100]) ?>
                                        </div>
                                <div class="ContentItem-head">
                                    <div class="ContentItem-title"><?= Html::a(User::get_nickname($id), ['site/people', 'id' => $id], ['class' => 'txt1']) ?></div>
                                            <div class="ContentItem-meta"><div><div class="RichText">前腾讯京东战略分析师，电商天使投资人</div><div class="ContentItem-status"><span class="ContentItem-statusItem">114 回答</span><span class="ContentItem-statusItem">265 文章</span><span class="ContentItem-statusItem">16342 关注者</span></div></div></div></div>
                                            <div class="ContentItem-extra"><button class="Button FollowButton Button--primary Button--blue" type="button">关注</button></div>
                                        </div>
                            <?php } ?>
                            <div class="text-center"><?= LinkPager::widget(['pagination' => $pages]); ?></div>
                        <?php } else { ?>
                            <div class="text-center no-data">没有内容</div>
                            <?php } ?>



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