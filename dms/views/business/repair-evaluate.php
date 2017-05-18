<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\RepairOrder;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'repair-order-evaluate-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <?=
        $form->field($model, 'evaluate1')->widget(StarRating::classname(), [
            'pluginOptions' => ['step' => 1, 'showClear' => false, 'size' => 'xs', 'starCaptions' => RepairOrder::$List['evaluate']]
        ])
        ?>
        <?=
        $form->field($model, 'evaluate2')->widget(StarRating::classname(), [
            'pluginOptions' => ['step' => 1, 'showClear' => false, 'size' => 'xs', 'starCaptions' => RepairOrder::$List['evaluate']]
        ])
        ?>
        <?=
        $form->field($model, 'evaluate3')->widget(StarRating::classname(), [
            'pluginOptions' => ['step' => 1, 'showClear' => false, 'size' => 'xs', 'starCaptions' => RepairOrder::$List['evaluate']]
        ])
        ?>

        <?= $form->field($model, 'note')->textarea(['maxlength' => true, 'rows' => '6'])->label('评价详情') ?>

        <div class="col-md-6 col-xs-6 text-right">

            <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-md-6 col-xs-6 text-left">
            <?= Html::resetButton('取消', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</div>