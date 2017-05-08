<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\RepairOrder;
use dms\models\System;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'repair-order-accept-form',
                    'enableAjaxValidation' => true,
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?php if (System::getValue('business_accept') === '1') { ?>

            <?= $form->field($model, 'stat')->radioList([RepairOrder::STAT_DISPATCH => '派工', RepairOrder::STAT_ACCEPT => '受理', RepairOrder::STAT_NO_ACCEPT => '不受理'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]])
            ?>

            <?= $form->field($model, 'worker_id')->dropDownList($model->getWorkerList($model->repair_type, $model->repair_area)) ?>
        <?php } else { ?>
            <?= $form->field($model, 'stat')->radioList([RepairOrder::STAT_ACCEPT => '受理', RepairOrder::STAT_NO_ACCEPT => '不受理'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]])
            ?>
        <?php } ?>
        <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

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
<?php $this->beginBlock('accept') ?>
    showview();
    $('#repairorder-stat input:radio').on('click', function () {
        showview();
    });

    function showview() {
        var stat = $('#repairorder-stat').find('input:radio:checked').val();
        if (stat ==<?= RepairOrder::STAT_DISPATCH ?>) {
            $('.field-repairorder-worker_id').show();
            $('.field-repairorder-note').hide();
        } else if (stat ==<?= RepairOrder::STAT_ACCEPT ?>) {
            $('.field-repairorder-worker_id').hide();
            $('.field-repairorder-note').hide();
        } else {
            $('.field-repairorder-worker_id').hide();
            $('.field-repairorder-note').show();
        }
    }

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['accept'], \yii\web\View::POS_END); ?>