<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\Room;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairWorker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'repair-worker-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">

                <?= $form->field($model, 'unit_id')->dropDownList($model->get_unit_id(), ['prompt' => '无']) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'type')->checkboxList($model->get_type_id(), ['itemOptions' => ['labelOptions' => ['class' => 'checkbox-inline']]]) ?>

                <?= $form->field($model, 'area')->checkboxList(Room::get_forum_id(), ['itemOptions' => ['labelOptions' => ['class' => 'checkbox-inline']]]) ?>

                <?= $form->field($model, 'stat')->radioList(\dms\models\RepairWorker::$List['stat'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

                <?= $form->field($model, 'uid')->dropDownList($model->get_user_id(), ['prompt' => '无', 'class' => 'form-control select2']) ?>

            </div>
            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
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
