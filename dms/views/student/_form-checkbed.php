<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\CheckOrder;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairWorker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'checkbed-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>
        <?= $form->field($model, 'bed')->dropDownList(CheckOrder::get_bed($model->related_id), ['prompt' => '无', 'class' => 'form-control select2']) ?>

        <?= $form->field($model, 'stat')->radioList(CheckOrder::$List['stat'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>


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
<?php $this->beginBlock('js') ?>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>
