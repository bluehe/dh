<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dh\models\Recommend;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model dh\models\Recommend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'recommend-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                        ],
            ]);
            ?>
            <div class="box-body">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

                <?=
                        $form
                        ->field($model, 'img', [
                            'template' => "{label}\n<div class=\"col-md-4\">{input}" . FileInput::widget([
                                'name' => 'file',
                                'pluginOptions' => [
                                    'language' => 'zh',
                                    //关闭按钮
                                    'showPreview' => false,
                                    'showCancel' => false,
                                    'showCaption' => false,
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    //浏览按钮样式
                                    'browseClass' => 'btn btn-primary',
                                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                                    'browseLabel' => '上传图片',
                                    'buttonLabelClass' => '',
                                    //错误提示
                                    'elErrorContainer' => '#kv-fileinput-error',
                                    //进度条
                                    'progressUploadThreshold' => 90,
                                    //上传
                                    'uploadAsync' => true,
                                    'uploadUrl' => Url::toRoute(['upload-logo']),
                                    'uploadExtraData' => ['dir' => 'tmp'],
                                    'maxFileSize' => $maxsize,
                                ],
                                'options' => ['accept' => 'image/*'],
                                'pluginEvents' => [
                                    //选择后直接上传
                                    'change' => 'function() {$(this).fileinput("upload");}',
                                    //完成后隐藏进度条
                                    'filebatchuploadcomplete' => 'function() {$(".kv-upload-progress").addClass("hide");}',
                                    //上传成功
                                    'fileuploaded' => 'function(event, data) {$("#logo-crop .modal-body").html("");$.post("logo-crop",{url:data.response.urls},function(data){$("#logo-modal").html(data);});}',
                                ],
                            ]) . Html::img($model->img, ['class' => 'img-rounded recommend-img', 'style' => 'margin-top:10px']) . "</div>\n<div class=\"col-md-6\">{error}</div>"
                        ])
                        ->hiddenInput()
                ?>



                <?= $form->field($model, 'sort_order')->textInput() ?>

                <?= $form->field($model, 'stat')->radioList(Recommend::$List['stat'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline']]]) ?>

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
    <div id="logo-modal"></div>
</div>
