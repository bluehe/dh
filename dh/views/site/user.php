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
        <section class="col-lg-3">
            <?=
            $this->render('left.php')
            ?>


        </section>
        <section class="col-lg-9">
            <div class="row">
                <?php Pjax::begin(); ?>
                <div class="website plate-<?= Yii::$app->params['plate'] ?> col-lg-12 categorySortable">
                    <?php foreach ($cates as $cate) { ?>
                        <div class="category" id="<?= $cate['id'] ?>">

                            <div class="website-header">

                                <b><?= $cate['title'] ?></b>
                                <span class="header-icon"><i class="fa fa-edit category-edit" title="编辑分类"></i><i class="fa fa-trash-o category-delete"  title="删除分类"></i> <i class="fa fa-plus category-add"  title="添加分类"></i></span>
                                <div class="pull-right add_page website-add" title="添加网址"> <i class="fa fa-plus"></i></div>

                            </div>
                                <div class="website-content list-group websiteSortable">

                                <?php foreach ($cate['website'] as $website) { ?>
                                    <div class="list-group-item<?= $website['is_open'] == Website::ISOPEN_OPEN ? '' : ' list-group-item-warning' ?>" id="<?= $website['id'] ?>">
                                                <?= Html::img('@web/image/default_ico.png', ['class' => 'lazyload', 'data-original' => Url::to(Yii::$app->params['img_url'] . '/api/getfav?url=' . $website['host'])]) ?>
                                                <a class="clickurl" target="_blank" href="<?= $website['url'] ?>" title="<?= $website['title'] ?>"><?= $website['title'] ?></a>
                                        <div class="dropdown pull-right">
                                            <span class="dropdown-toggle" id="dropdownMenu<?= $website['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="fa fa-caret-square-o-down" title="操作"></i>
                                            </span>
                                                    <div class="dropdown-menu content-icon <?= $website['share_status'] == Website::SHARE_DEFAULT ? '' : 'list3' ?>" aria-labelledby="dropdownMenu<?= $website['id'] ?>">
                                                        <?= $website['share_status'] == Website::SHARE_DEFAULT ? '<i class="fa fa-share-alt website-share" title="分享"></i>' : '' ?>
                                                <i class="fa fa-edit website-edit" title="编辑" ></i>
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
    $(".categorySortable").sortable({
        placeholder: "category sort-highlight",
        containment: ".website",
        handle: ".website-header",
        opacity: 0.8,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        revert: true,
        tolerance: "pointer",
        update: function (event, ui) {
            var categoryids = $(this).sortable("toArray");
            var id = ui.item[0].id;
            var sort = $.inArray(id, categoryids) + 1;
            if (sort > 0) {
                $.getJSON("<?= Url::toRoute('ajax/category-sort') ?>", {id: id, sort: sort}, function (data) {
                    if (data.stat === 'fail') {
                        my_alert('danger', data.msg, 3000);
                    }
                });
            } else {
                my_alert('danger', '排序出错', 3000);
            }

        }
    });
        $(".websiteSortable").sortable({
        placeholder: "list-group-item sort-highlight",
        containment: ".website",
        connectWith: ".websiteSortable",
        opacity: 0.8,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        revert: true,
        tolerance: "pointer",
        receive: function (event, ui) {
        console.log('receive'+ui.item[0].id);
            console.log($(this).sortable("toArray"));

        },
         remove: function (event, ui) {
        console.log('remove'+ui.item[0].id);
            console.log($(this).sortable("toArray"));

        },
        update: function (event, ui) {
        console.log('update'+ui.item[0].id);
            console.log($(this).sortable("toArray"));

        }
    });
    //分类编辑
    $('.website').on('click', '.category-edit', function () {

        $.get('<?= Url::toRoute('ajax/category-edit') ?>', {id: $(this).parents('.category').attr('id')},
                function (data) {
                    $('#user-modal .modal-title').html('编辑分类');
                    $('#user-modal .modal-body').html(data);
                    $('#user-modal').modal('show');
                }
        );
    });
    //分类删除
    $('.website').on('click', '.category-delete', function () {
        var _this = $(this).parents('.category');
        var id = _this.attr('id');
        if (id) {
            $.getJSON("<?= Url::toRoute('ajax/category-delete') ?>", {id: id}, function (data) {
                if (data.stat === 'success') {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                } else if (data.stat === 'fail') {
                    my_alert('danger', data.msg, 3000);
                }
            });
        }
    });
    //分类添加
    $('.website').on('click', '.category-add', function () {
        var _this = $(this).parents('.category');
        var id = _this.attr('id');
        if (id) {
            $.get("<?= Url::toRoute('ajax/category-add') ?>", {id: id}, function (data) {
                $('#user-modal .modal-title').html('添加分类');
                $('#user-modal .modal-body').html(data);
                $('#user-modal').modal('show');
            });
        }
    });
    //网址添加
    $('.website').on('click', '.website-add', function () {
        $.get('<?= Url::toRoute('ajax/website-add') ?>', {id: $(this).parents('.category').attr('id')},
                function (data) {
                    $('#user-modal .modal-title').html('添加网址');
                    $('#user-modal .modal-body').html(data);
                    $('#user-modal').modal('show');
                }
        );
    });
    //网址分享
    $('.website').on('click', '.website-share', function () {
        $.get('<?= Url::toRoute('ajax/website-share') ?>', {id: $(this).parents('.list-group-item').attr('id')},
                function (data) {
                    $('#user-modal .modal-title').html('网址分享');
                    $('#user-modal .modal-body').html(data);
                    $('#user-modal').modal('show');
                }
        );
    });
    //网址编辑
    $('.website').on('click', '.website-edit', function () {
        $.get('<?= Url::toRoute('ajax/website-edit') ?>', {id: $(this).parents('.list-group-item').attr('id')},
                function (data) {
                    $('#user-modal .modal-title').html('编辑网址');
                    $('#user-modal .modal-body').html(data);
                    $('#user-modal').modal('show');
                }
        );
    });
    //网址删除
    $('.website').on('click', '.website-delete', function () {
        var _this = $(this).parents('.list-group-item');
        var id = _this.attr('id');
        if (id) {
            $.getJSON("<?= Url::toRoute('ajax/website-delete') ?>", {id: id}, function (data) {
                if (data.stat === 'success') {
                    _this.remove();
                    my_alert('success', '删除成功！', 3000);
                } else if (data.stat === 'fail') {
                    my_alert('danger', data.msg, 3000);
                }
            });
        }
    });
    //网址公开/私有
    $('.website').on('click', '.website-open', function () {
        var _this = $(this);
        var id = _this.parents('.list-group-item').attr('id');
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

