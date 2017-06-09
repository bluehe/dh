<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model dms\models\CheckOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php $form = ActiveForm::begin(['id' => 'check-order-form',
            'options' => [ 'class' => 'form-horizontal'],
            'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-2 control-label'],
            ],
            ]); ?>
            <div class="box-body">
                    <?= $form->field($model, 'related_table')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'related_id')->textInput() ?>

    <?= $form->field($model, 'bids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'checkout_at')->textInput() ?>

    <?= $form->field($model, 'created_uid')->textInput() ?>

    <?= $form->field($model, 'updated_uid')->textInput() ?>

    <?= $form->field($model, 'checkout_uid')->textInput() ?>

    <?= $form->field($model, 'stat')->textInput() ?>

            </div>
            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= Html::resetButton( '重置' , ['class' => 'btn btn-default']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
