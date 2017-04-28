<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\Room;
use dms\models\RepairWorker;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'repair-order-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">
                <?= $form->field($model, 'repair_type')->dropDownList(RepairWorker::get_type_id(), ['prompt' => '无']) ?>

                <?= $form->field($model, 'repair_area')->dropDownList(Room::get_forum_id(), ['prompt' => '无']) ?>

                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'rows' => '6']) ?>


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
