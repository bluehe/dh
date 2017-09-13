<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use dh\components\GoLinkPager;

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
                <?php Pjax::begin(['id' => 'pjax_index']); ?>
                <div class="website plate-<?= Yii::$app->params['plate'] ?> col-lg-12">
                    <?php foreach ($cates as $cate) { ?>
                        <div class="category" data-id="<?= $cate['id'] ?>">
                            <div class="website-header">
                                <b><?= $cate['title'] ?></b>
                                <span class="header-icon pull-right"><i class="fa fa-star-o category-collect" title="收藏分类" data-toggle="modal" data-target="#collect-modal"></i></span>
                            </div>
                            <div class="website-content list-group">

                                <?php foreach ($cate['website'] as $website) { ?>
                                    <div class="list-group-item" data-id="<?= $website['id'] ?>">
                                        <?= Html::img(['api/getfav', 'url' => $website['url']]) ?>
                                        <a class="clickurl" target="_blank" href="<?= $website['url'] ?>" title="<?= $website['title'] ?>"><?= $website['title'] ?></a>
                                        <div class="content-icon index-icon pull-right" >
                                            <i class="fa fa-heart-o website-collect" title="收藏网址" data-toggle="modal" data-target="#collect-modal"></i>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="col-lg-12 col-xs-12 text-center">
                    <?=
                    GoLinkPager::widget([
                        'pagination' => $pages, 'maxButtonCount' => 5,
                        'go' => true,
                        'linkOptions' => ['data-pjax' => '#pjax_index']
                    ]);
                    ?>
                </div>
                <?php Pjax::end(); ?>
            </div>

        </section>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'collect-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('collect') ?>
    $('.category-collect').on('click', function () {
        $('#collect-modal .modal-title').html('');
        $('#collect-modal .modal-body').html('');
        $.get('<?= Url::toRoute('ajax/category-collect') ?>', {id: $(this).parents('.category').data('id')},
                function (data) {
                    $('#collect-modal .modal-title').html('收藏分类');
                    $('#collect-modal .modal-body').html(data);
                }
        );
    });

    $('.website-collect').on('click', function () {
        $('#collect-modal .modal-title').html('');
        $('#collect-modal .modal-body').html('');
        $.get('<?= Url::toRoute('ajax/website-collect') ?>', {id: $(this).parents('.list-group-item').data('id')},
                function (data) {
                    $('#collect-modal .modal-title').html('收藏网址');
                    $('#collect-modal .modal-body').html(data);
                }
        );
    });

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['collect'], \yii\web\View::POS_END); ?>