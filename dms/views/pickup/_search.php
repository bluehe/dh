<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model dms\models\PickupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pickup-search">
    <div class="box box-info">
        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>
        <div class="box-body">
                <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'goods') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'end_at') ?>

    <?php // echo $form->field($model, 'stat') ?>

        </div>
        <div class="box-footer">
            <div class="col-lg-1 col-lg-offset-2 col-xs-6 text-right">
                <?= Html::submitButton( '搜索' , ['class' => 'btn btn-primary']) ?>
            </div>
            <div class="col-lg-1 col-xs-6 text-left">
                <?= Html::resetButton( '重置' , ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
