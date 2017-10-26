<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dh\models\Category;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairWorker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'website-form',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?= $form->field($model, 'cid')->dropDownList([], ['prompt' => '无']) ?>

        <?= $form->field($model, 'cid_note')->textInput(['maxlength' => true]) ?>

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
    $(document).ready(function () {
        changeshow();
        $('#websiteshare-cid').on('change', function () {
            changeshow();
        });

    });
    function changeshow() {
        var forum = $('.radio-business_forum:checked').val();
        var roomtype = $('.radio-business_roomtype:checked').val();
        if (forum === '1') {
            $('.field-business_room').show();
        } else {
            $('.field-business_room').hide();
        }
        if (roomtype === '1') {
            $('.field-business_bed').show();
        } else {
            $('.field-business_bed').hide();
        }
    }
    $('body').off('submit').on('submit', '#website-form', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/website-share', 'id' => $model->wid]) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.stat === 'success') {
                    $('#user-modal').modal('hide');
                    $('.list-group-item[data-id=<?= $model->wid ?>]').find('.dropdown-menu').addClass('no-share');
                    $('.list-group-item[data-id=<?= $model->id ?>]').find('.dropdown-menu .fa-share-alt').remove();
                    my_alert('success', '分享成功！', 3000);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>