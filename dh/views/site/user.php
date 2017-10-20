<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
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
                                <span class="header-icon"><i class="fa fa-edit category-edit" title="编辑分类" data-toggle="modal" data-target="#user-modal"></i><i class="fa fa-trash-o category-delete"  title="删除分类"></i></span>
                                <div class="pull-right add_page category-add" title="添加分类"> <i class="fa fa-plus"></i></div>

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
                                                <i class="fa fa-share-alt" title="推荐分享" data-toggle="modal" data-target="#user-modal"></i>
                                                <i class="fa fa-edit website-edit" title="编辑" data-toggle="modal" data-target="#user-modal"></i>
                                                <i class="fa fa-trash-o website-delete" title="删除"></i>
                                                <i class="fa <?= $website['is_open'] == Website::ISOPEN_OPEN ? 'fa-eye-slash' : 'fa-eye' ?> website-open" title="<?= $website['is_open'] == Website::ISOPEN_OPEN ? '私有' : '公开' ?>"></i>
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
    //分类编辑
    $('.website').on('click', '.category-edit', function () {
        $('#user-modal .modal-title').html('');
        $('#user-modal .modal-body').html('');
        $.get('<?= Url::toRoute('ajax/category-edit') ?>', {id: $(this).parents('.category').data('id')},
                function (data) {
                    $('#user-modal .modal-title').html('编辑分类');
                    $('#user-modal .modal-body').html(data);
                }
        );
    });

    //分类删除
    $('.website').on('click', '.category-delete', function () {
        var _this = $(this).parents('.category');
        var id = _this.data('id');
        if (id) {
            $.get("<?= Url::toRoute('ajax/category-delete') ?>", {id: id}, function (result) {
                if (result) {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                }
            });
        }
    });

    //分类添加



    //网址添加

    //网址分享

    //网址编辑
    $('.website').on('click', '.website-edit', function () {
        $('#user-modal .modal-title').html('');
        $('#user-modal .modal-body').html('');

        $.get('<?= Url::toRoute('ajax/website-edit') ?>', {id: $(this).parents('.list-group-item').data('id')},
                function (data) {
                    $('#user-modal .modal-title').html('编辑网址');
                    $('#user-modal .modal-body').html(data);
                }
        );
    });

    //网址删除
    $('.website').on('click', '.website-delete', function () {
        var _this = $(this).parents('.list-group-item');
        var id = _this.data('id');
        if (id) {
            $.get("<?= Url::toRoute('ajax/website-delete') ?>", {id: id}, function (result) {
                if (result) {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                }
            });
        }
    });

    //网址公开/私有
    $('.website').on('click', '.website-open', function () {
        var _this = $(this);
        var id = _this.parents('.list-group-item').data('id');
        if (id) {
            $.get("<?= Url::toRoute('ajax/website-open') ?>", {id: id}, function (data) {
                if (data) {
                    if (data === 'open') {
                        _this.parents('.list-group-item').removeClass('list-group-item-warning');
                        _this.removeClass('fa-eye').addClass('fa-eye-slash');
                        _this.attr('title', '私有');

                    } else {
                        _this.parents('.list-group-item').addClass('list-group-item-warning');
                        _this.removeClass('fa-eye-slash').addClass('fa-eye');
                        _this.attr('title', '公开');
                    }
                    my_alert('success', '操作成功！', 3000);
                }
            });
        }
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>
