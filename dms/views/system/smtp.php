<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '邮件设置';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'smtp-form',
                        'options' => ['class' => 'form-horizontal'],
            ]);
            ?>
            <div class="box-body">
                <?php
                foreach ($model as $one) {
                    if ($one['type'] == 'text' || $one['type'] == 'password') {
                        ?>
                        <div class="form-group"><label class="col-md-2 control-label" for="<?= $one['code'] ?>"><?= $one['tag'] ?></label><div class="col-md-4"><input type="<?= $one['type'] ?>" id="<?= $one['code'] ?>" class="form-control" name="System[<?= $one['code'] ?>]" value="<?= $one['value'] ?>"></div><div class="col-md-6"><div class="help-block"></div></div></div>
                        <?php
                    } elseif ($one['type'] == 'radio' && $ranges = json_decode($one['store_range'])) {
                        ?>
                        <div class="form-group"><label class="col-md-2 control-label"><?= $one['tag'] ?></label>
                            <div class="col-md-4">
                                <div id="<?= $one['code'] ?>">
                                    <?php
                                    foreach ($ranges as $key => $range) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="System[<?= $one['code'] ?>]" value="<?= $key ?>" <?= "$key" == $one['value'] ? 'checked="checked"' : '' ?>> <?= $range ?></label>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-md-6"><div class="help-block"><?= $one['code'] == 'smtp_service' ? '如果您选择了采用系统预设的 Mail 服务，您不需要填写下面的内容。' : '' ?></div></div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="form-group"><label class="col-md-2 col-xs-12 control-label" for="smtp-test">测试邮件地址</label><div class="col-md-4 col-xs-7"><input type="text" id="smtp-test" class="form-control" name="test" value=""></div><div class="col-md-6 col-xs-5"><input type="button" value="发送测试邮件" class="btn btn-primary testemail"></div></div>
            </div>
            <div class="box-footer">
                <div class="col-md-1 col-md-offset-2 col-xs-6 text-right">
                    <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default', 'name' => 'update-button']) ?>
                </div>

            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
<?php $this->beginBlock('sendemail') ?>
    $('.testemail').on('click', function () {
        var $email = $('#smtp-test').val();
        var _this = $(this);
        if ($email && _this.hasClass('btn-primary')) {
            _this.addClass('btn-default').removeClass('btn-primary');
            $.ajax({
                type: "POST",
                url: '/system/send-email',
                data: {'email': $email},
                success: function (data) {
                    if (data == 'success') {
                        $('.content').prepend('<div id="w0-success" class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>测试邮件发送成功。</div>');
                    } else {
                        $('.content').prepend('<div id="w0-error" class="alert-error alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>测试邮件发送失败。</div>');
                    }
                    _this.addClass('btn-primary').removeClass('btn-default');
                },
                error: function () {
                    $('.content').prepend('<div id="w0-error" class="alert-error alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>测试邮件发送失败。</div>');
                    _this.addClass('btn-primary').removeClass('btn-default');
                }
            });
        }
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['sendemail'], \yii\web\View::POS_END); ?>
