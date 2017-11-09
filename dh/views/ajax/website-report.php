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
        $form = ActiveForm::begin(['id' => 'website-form',
                    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <div class="form-group">
            <label class="col-md-3 control-label"><?= $model->getAttributeLabel('title') ?></label>
            <div class="col-md-6" style="padding-top: 7px;"><?= $model->title ?></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><?= $model->getAttributeLabel('url') ?></label>
            <div class="col-md-6" style="padding-top: 7px;"><?= $model->url ?></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label">举报内容</label>
            <div class="col-md-6"><textarea id="website-report-content" class="form-control" name="content" rows="6"></textarea></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>


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
    $('body').off('submit').on('submit', '#website-form', function () {
        $.ajax({
            url: '<?= Url::toRoute(['ajax/website-report', 'id' => $model->id]) ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                $('#collect-modal').modal('hide');
                if (data.stat === 'success') {
                    my_alert('success', '举报成功！', 3000);
                } else if (data.stat === 'fail') {
                    my_alert('danger', data.msg);
                }

            }
        });
    });


<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['submit'], \yii\web\View::POS_END); ?>