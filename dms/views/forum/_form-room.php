<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\Room;

/* @var $this yii\web\View */
/* @var $model dms\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'broom-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">
                <?=
                $form->field($model, 'fid')->dropDownList($model->get_forum_id(), ['prompt' => '请选择',
                    'onchange' => '$.post("' . Yii::$app->urlManager->createUrl('forum/broom-list') . '?fid="+$(this).val()+"&floor="+$("#room-floor").find("option:selected").val()+"&new=' . $model->isNewRecord . '&id=' . $model->id . '",function(data){$("select#room-rid").html(data);});'
                ])
                ?>

                <?=
                $form->field($model, 'floor')->dropDownList($model->get_floor_id(), ['prompt' => '请选择',
                    'onchange' => '$.post("' . Yii::$app->urlManager->createUrl('forum/broom-list') . '?fid="+$("#room-fid").find("option:selected").val()+"&floor="+$(this).val()+"&new=' . $model->isNewRecord . '&id=' . $model->id . '",function(data){$("select#room-rid").html(data);});'])
                ?>

                <?= $form->field($model, 'rid')->dropDownList($model->getBroomList($model->fid, $model->floor, $model->id), $model->isNewRecord ? ['multiple' => true, 'class' => 'form-control select2'] : ['prompt' => '无', 'class' => 'form-control select2']) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'stat')->radioList(Room::$List['stat'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

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