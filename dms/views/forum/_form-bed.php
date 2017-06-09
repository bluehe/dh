<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dms\models\Bed;
use dms\models\Room;

/* @var $this yii\web\View */
/* @var $model dms\models\Bed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'bed-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">
                <?=
                $form->field($model, 'fid')->dropDownList(Room::get_forum_id(), ['prompt' => '请选择',
                    'onchange' => '$.post("' . Yii::$app->urlManager->createUrl('forum/room-list') . '?fid="+$(this).val()+"&floor="+$("#bed-flid").find("option:selected").val(),function(data){$("select#bed-rid").html(data);});'
                ])
                ?>

                <?=
                $form->field($model, 'flid')->dropDownList(Room::get_floor_id(), ['prompt' => '请选择',
                    'onchange' => '$.post("' . Yii::$app->urlManager->createUrl('forum/room-list') . '?fid="+$("#bed-fid").find("option:selected").val()+"&floor="+$(this).val(),function(data){$("select#bed-rid").html(data);});'])
                ?>

                <?= $form->field($model, 'rid')->dropDownList($model->getRoomList($model->fid, $model->flid), $model->isNewRecord ? ['multiple' => true, 'class' => 'form-control select2'] : ['prompt' => '无', 'class' => 'form-control select2']) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'stat')->radioList(Bed::$List['stat'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

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