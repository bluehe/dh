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
        $form = ActiveForm::begin(['id' => 'suggest-form',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

        <div class = "col-md-6 col-xs-6 text-right">

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
    $('body').off('submit').on('submit', '#suggest-form', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/suggest']) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                $('#suggest-modal').modal('hide');
                if (data.stat === 'success') {
                    my_alert('success', '提交成功！', 3000);
                } else if (data.stat === 'fail') {
                    my_alert('danger', data.msg, 3000);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>