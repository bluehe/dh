<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dh\models\User;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="row">
    <div class="col-md-12">

        <?php
        $form = ActiveForm::begin(['id' => 'suggest-reply-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                    ],
        ]);
        ?>

        <div class="form-group">
            <label class="col-md-3 control-label"><?= $model->getAttributeLabel('created_at') ?></label>
            <div class="col-md-6" style="padding-top: 7px;"><?= date('Y-m-d H:i:s', $model->created_at) ?></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?= $model->getAttributeLabel('uid') ?></label>
            <div class="col-md-6" style="padding-top: 7px;"><?= User::get_nickname($model->uid) ?></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><?= $model->getAttributeLabel('content') ?></label>
            <div class="col-md-6" style="padding-top: 7px;"><?= $model->content ?></div>
            <div class="col-md-3"><div class="help-block"></div></div>
        </div>


        <?= $form->field($model, 'reply_content')->textarea(['rows' => '6']) ?>

        <div class="col-md-6 col-xs-6 text-right">

            <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-md-6 col-xs-6 text-left">
            <?= Html::resetButton('取消', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</div>