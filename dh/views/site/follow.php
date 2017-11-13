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
                            <div class="ContentItem-main">
                                        <div class="ContentItem-image">
                                                <?= Html::a(Html::img(User::get_avatar($id), ['class' => 'img-rounded', 'width' => 70, 'height' => 70]), ['site/people', 'id' => $id]) ?>

                                            </div>
                                <div class="ContentItem-head">
                                    <div class="ContentItem-title"><?= Html::a(User::get_nickname($id), ['site/people', 'id' => $id], ['class' => 'txt1']) ?></div>
                                    <div class="ContentItem-meta"><div><div class="RichText">前腾讯京东战略分析师，电商天使投资人</div><div class="ContentItem-status"><span class="ContentItem-statusItem">114 回答</span><span class="ContentItem-statusItem">265 文章</span><span class="ContentItem-statusItem">16342 关注者</span></div></div></div>
                                        </div>
                                        <div class="ContentItem-extra">

                                                                <?php
                  
                                                                echo Yii::$app->user->isGuest || !UserAtten::is_atten(Yii::$app->user->identity->id, $id) ? Html::tag('button', '<i class="fa fa-plus"></i> 关注', ['class' => 'btn btn-primary user-follow', 'data-id' => $id]) : Html::tag('button', '已关注', ['class' => 'btn btn-default user-unfollow', 'data-id' => $id]);
                                                    ?>


                                        </div>
                            </div>
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
//用户取消关注
    $('.follow').on("mouseover", '.user-unfollow', function () {
        $(this).html('取消关注');
    }).on("mouseout", '.user-unfollow', function () {
        $(this).html('已关注');
    });
    $('.follow').on('click', '.user-unfollow', function () {
        var _this=$(this);
        $.getJSON("<?= Url::toRoute('ajax/user-unfollow') ?>", {user_id: _this.data('id')}, function (data) {
            if (data.stat === 'success') {
                _this.addClass('btn-primary user-follow').html('<i class="fa fa-plus"></i> 关注').removeClass('btn-default user-unfollow');
                //$('.user_atten li strong[data-type=fans]').html(parseInt($('.user_atten li strong[data-type=fans]').html()) - 1);
                //my_alert('success', data.msg, 3000);
            } else if (data.stat === 'fail') {
                my_alert('danger', data.msg, 3000);
            }
        });

    });
    $('.follow').on('click', '.user-follow', function () {
    var _this=$(this);
        $.getJSON("<?= Url::toRoute('ajax/user-follow') ?>", {user_id: _this.data('id')}, function (data) {
            if (data.stat === 'success') {
                _this.addClass('btn-default user-unfollow').html('已关注').removeClass('btn-primary user-follow');
                //$('.user_atten li strong[data-type=fans]').html(parseInt($('.user_atten li strong[data-type=fans]').html()) + 1);
                //my_alert('success', data.msg, 3000);
            } else if (data.stat === 'fail') {
                my_alert('danger', data.msg, 3000);
            }
        });

    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['follow'], \yii\web\View::POS_END); ?>