<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\Suggest;

/* @var $this yii\web\View */
/* @var $model dms\models\Pickup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'suggest-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">
                <?= $form->field($model, 'type')->radioList(Suggest::$List['type'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

                <?= $form->field($model, 'content')->textarea(['rows' => '6']) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
