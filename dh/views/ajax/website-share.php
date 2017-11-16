<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dh\models\Category;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'website-share',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?= $form->field($model, 'cid')->dropDownList(Category::get_user_category(), ['prompt' => '新增', 'class' => 'form-control select2']) ?>

        <?= $form->field($model, 'cname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

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
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        changeshow();
        $('#websiteshare-cid').on('change', function () {
            changeshow();
        });

    });
    function changeshow() {
        var forum = $('#websiteshare-cid').val();
        if (forum === '') {
            $('.field-websiteshare-cname').show();
        } else {
            $('.field-websiteshare-cname').hide();
        }

    }
    $('body').off('submit').on('submit', '#website-share', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/website-share', 'id' => $model->wid]) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                $('#user-modal').modal('hide');
                if (data.stat === 'success') {
                    $('.list-group-item[id=<?= $model->wid ?>]').find('.dropdown-menu').addClass('list3');
                    $('.list-group-item[id=<?= $model->wid ?>]').find('.dropdown-menu .fa-share-alt').remove();
                    my_alert('success', '分享成功！', 3000);
                } else {
                    my_alert('danger', data.msg, 3000);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>