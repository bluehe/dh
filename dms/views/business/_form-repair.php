<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dms\models\Room;
use dms\models\RepairWorker;
use kartik\file\FileInput;

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


                <?= $form->field($model, 'repair_area')->dropDownList(Room::get_forum_id(), ['prompt' => '无']) ?>

                <?= $form->field($model, 'repair_type')->dropDownList(RepairWorker::get_type_id(), ['prompt' => '无']) ?>

                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'rows' => '6']) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

                <?=
                $form->field($model, 'images[]')->widget(FileInput::classname(), [
                    'name' => 'files[]',
                    'options' => ['multiple' => true, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        'language' => 'zh',
                        //上传
                        'uploadAsync' => true,
                        'uploadUrl' => Url::toRoute(['business/upload-image']),
                        'uploadExtraData' => ['dir' => 'repair'],
                        'maxFileSize' => $maxsize,
                        'maxFileCount' => 5,
                        'autoReplace' => true,
                        //关闭按钮
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'showCancel' => false,
                        //图像大小
                        'resizeImage' => true,
                        'maxImageWidth' => 200,
                        'maxImageHeight' => 150,
                        'resizePreference' => 'height',
                        //浏览按钮样式
                        'browseClass' => 'btn btn-primary',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => '上传图片',
                        'buttonLabelClass' => '',
                        'browseOnZoneClick' => true,
                        'layoutTemplates' => 'main2',
                        'fileActionSettings' => [
                            // 设置具体图片的查看属性为false,默认为true
                            'showZoom' => true,
                            // 设置具体图片的上传属性为true,默认为true
                            'showUpload' => true,
                            // 设置具体图片的移除属性为true,默认为true
                            'showRemove' => true,
                        ],
                    ],
                    'pluginEvents' => [
                        //选择后直接上传
                        'filebatchselected' => 'function() {$(this).fileinput("upload");}',
                        //完成后隐藏进度条
                        'filebatchuploadcomplete' => 'function() {$(".kv-upload-progress").addClass("hide");}',
                        //上传成功
                        'fileuploaded' => 'function(event, data) {console.log(data);}',
                    ],
                ])
                ?>

            </div>


            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>

            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
</div>
