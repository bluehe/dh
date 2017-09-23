<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dms\models\Room;
use dms\models\RepairWorker;
use kartik\file\FileInput;
use dms\models\System;

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

                <?php
                if (System::getValue('repair_image') === '2') {
                    echo $form->field($model, 'images[]')->widget(FileInput::classname(), [
                        'options' => ['multiple' => true, 'accept' => 'image/*'],
                        'pluginOptions' => [
                            'language' => 'zh',
                            //上传
                            'uploadAsync' => true,
                            'uploadUrl' => Url::toRoute(['business/upload-image']),
                            'uploadExtraData' => ['dir' => 'repair'],
                            'maxFileSize' => $maxsize,
                            //关闭按钮
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showCancel' => false,
                            'initialPreviewConfig' => ['width' => '200px'],
                            //图像大小
                            'resizeImage' => true,
                            'maxImageWidth' => 500,
                            //'maxImageHeight' => 150,
                            //'resizePreference' => 'height',
                            //浏览按钮样式
                            'previewFileType' => 'image',
                            'browseClass' => 'btn btn-primary',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' => '上传图片',
                            'buttonLabelClass' => '',
                            'browseOnZoneClick' => true,
                            'fileActionSettings' => [
                                // 设置具体图片的查看属性为false,默认为true
                                'showZoom' => false,
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
                            'fileuploaded' => 'function(event, data) {$(this).prepend(ata.response.urls[0]));}',
                            'fileclear' => 'function(event) {console.log("fileclear");}',
                            'filecleared' => 'function(event) {console.log("filecleared");}',
                            'fileremoved' => 'function(event,id,index){console.log("fileremoved");}',
                            'filedeleted' => 'function(event, key, jqXHR, data) {console.log("filedeleted");}',
                            'filesuccessremove' => 'function(event, id) {console.log(id)}',
                        ],
                    ]);
                }
                ?>

            </div>


            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

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
