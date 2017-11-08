<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'category-form',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="col-md-6 col-xs-6 text-right">

            <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-md-6 col-xs-6 text-left">
            <?= Html::resetButton('取消', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</div>

<script>
<?php $this->beginBlock('submit') ?>
    $('body').off('submit').on('submit', '#category-form', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/category-add', 'id' => $id]) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.stat === 'success') {
                    var str = '<div class="category" id="' + data.id + '">'
                            + '<div class="website-header">'
                            + '<b>' + data.title + '</b>'
                            + '<span class="header-icon"><i class="fa fa-edit category-edit" title="编辑分类"></i><i class="fa fa-trash-o category-delete" title="删除分类"></i> <i class="fa fa-plus category-add" title="添加分类"></i></span>'
                            + '<div class="pull-right add_page website-add" title="添加网址"> <i class="fa fa-plus"></i></div>'
                            + '</div>'
                            + '<div class="website-content list-group websiteSortable"></div>'
                            + '</div>';
                    $('#user-modal').modal('hide');
                    $('.category[id=<?= $id ?>]').after(str);

    $(".websiteSortable").sortable({
        placeholder: "list-group-item sort-highlight",
        containment: ".website",
        connectWith: ".sort",
        opacity: 0.8,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        revert: true,
        tolerance: "pointer",
        update: function (event, ui) {
            var cid=$(this).parents('.category').attr('id');
            var websiteids = $(this).sortable("toArray");
            var id = ui.item[0].id;
            var sort = $.inArray(id, websiteids) + 1;
            if (sort > 0) {
                $.getJSON("<?= Url::toRoute('ajax/website-sort') ?>", {id: id, sort: sort,cid:cid}, function (data) {
                     if(data.stat==='success'){
                        $('.category').each(function(){
                            var l=$(this).find('.website-content .list-group-item').length;
                            if(l>=10){
                                $(this).find('.add_page').hide();
                                $(this).find('.websiteSortable').removeClass('sort');
                            }else{
                                $(this).find('.add_page').show();
                                $(this).find('.websiteSortable').addClass('sort');
                            }
                        });
                    }else if (data.stat === 'fail') {
                        my_alert('danger', data.msg, 3000);
                    }
                });
            }

        }

    });
                    my_alert('success', '添加成功！', 3000);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>