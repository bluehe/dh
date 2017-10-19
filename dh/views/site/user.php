<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use dh\models\Website;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

$this->title = '我的网址';
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
                        <div class="category" data-id="<?= $cate['id'] ?>">

                            <div class="website-header">

                                <b><?= $cate['title'] ?></b>
                                <span class="header-icon"><i class="fa fa-edit" title="编辑分类"></i><i class="fa fa-trash-o category-delete"  title="删除分类"></i></span>
                                <div class="pull-right add_page" title="添加分类"> <i class="fa fa-plus"></i></div>

                            </div>
                            <div class="website-content list-group">

                                <?php foreach ($cate['website'] as $website) { ?>
                                    <div class="list-group-item <?= $website['is_open'] == Website::ISOPEN_OPEN ? '' : 'list-group-item-warning' ?>" data-id="<?= $website['id'] ?>">
                                        <?= Html::img(['api/getfav', 'url' => $website['url']]) ?>
                                        <a class="clickurl" target="_blank" href="<?= $website['url'] ?>" title="<?= $website['title'] ?>"><?= $website['title'] ?></a>
                                        <div class="dropdown pull-right">
                                            <span class="dropdown-toggle" id="dropdownMenu<?= $website['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="fa fa-caret-square-o-down" title="操作"></i>
                                            </span>
                                            <div class="dropdown-menu content-icon" aria-labelledby="dropdownMenu<?= $website['id'] ?>">
                                                <i class="fa fa-share-alt" title="推荐分享"></i>
                                                <i class="fa fa-edit" title="编辑"></i>
                                                <i class="fa fa-trash-o website-delete" title="删除"></i>
                                                <i class="fa <?= $website['is_open'] == Website::ISOPEN_OPEN ? 'fa-eye-slash' : 'fa-eye' ?>" title="<?= $website['is_open'] == Website::ISOPEN_OPEN ? '私有' : '公开' ?>"></i>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php } ?>

                </div>

                <?php Pjax::end(); ?>
            </div>
        </section>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'user-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('js') ?>
    $('.website').on('click', '.website-delete', function () {
        var _this = $(this).parents('.list-group-item');
        var id = _this.data('id');
        if (id) {
            $.get("/ajax/website-delete", {id: id}, function (result) {
                if (result) {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                }
            });
        }
    });

    $('.website').on('click', '.category-delete', function () {
        var _this = $(this).parents('.category');
        var id = _this.data('id');
        if (id) {
            $.get("/ajax/category-delete", {id: id}, function (result) {
                if (result) {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                }
            });
        }
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>
