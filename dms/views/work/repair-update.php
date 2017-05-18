<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\RepairOrder;
use dms\models\Room;
use dms\models\RepairWorker;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'repair-order-update-form',
//                    'enableAjaxValidation' => true,
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?= $form->field($model, 'repair_type')->dropDownList(RepairWorker::get_type_id(), ['prompt' => '无']) ?>

        <?= $form->field($model, 'repair_area')->dropDownList(Room::get_forum_id(), ['prompt' => '无']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'rows' => '6']) ?>


        <div class="col-md-6 col-xs-6 text-right">

            <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-md-6 col-xs-6 text-left">
            <?= Html::resetButton('取消', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</div>