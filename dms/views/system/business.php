<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '业务设置';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?php
            $form = ActiveForm::begin(['id' => 'business-form',
                        'options' => ['class' => 'form-horizontal'],
            ]);
            ?>
            <div class="box-body">
                <?php
                foreach ($model as $one) {
                    if ($one['type'] == 'text') {
                        ?>
                        <div class="form-group field-<?= $one['code'] ?>"><label class="col-md-2 control-label" for="<?= $one['code'] ?>"><?= $one['tag'] ?></label><div class="col-md-4"><input type="<?= $one['type'] ?>" id="<?= $one['code'] ?>" class="form-control" name="System[<?= $one['code'] ?>]" value="<?= $one['value'] ?>"></div><div class="col-md-6"><div class="help-block"></div></div></div>
                        <?php
                    } elseif ($one['type'] == 'radio' && $ranges = json_decode($one['store_range'])) {
                        ?>
                        <div class="form-group field-<?= $one['code'] ?>"><label class="col-md-2 control-label"><?= $one['tag'] ?></label>
                            <div class="col-md-4">
                                <div id="<?= $one['code'] ?>">
                                    <?php
                                    foreach ($ranges as $key => $range) {
                                        ?>
                                        <label class="radio-inline"><input type="radio" class="radio-<?= $one['code'] ?>" name="System[<?= $one['code'] ?>]" value="<?= $key ?>" <?= "$key" == $one['value'] ? 'checked="checked"' : '' ?>> <?= $range ?></label>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-md-6"><div class="help-block">
                                    <?php
                                    switch ($one['code']) {
                                        case 'business_room':echo '选择“一级”允许所有一级及二级楼苑添加房间，选择“二级”只允许没有二级楼苑的一级楼苑及二级楼苑添加房间。';
                                            break;
                                        case 'business_bed':echo '选择“大室”允许没有小室的套间及所有小室添加床位，选择“小室”只允许套间小室添加床位。';
                                            break;
                                        case 'business_accept':echo '选择“派工”可以在受理页面直接派工，选择“受理”只能选择是否受理。';
                                            break;
                                        case 'business_dispatch':echo '选择“全部”显示全部维修人员，选择“匹配”只会选择符合类型和地区的维修人员（批量受理如果为直接派工，将自动随机分派）。';
                                            break;
                                        case 'business_repairupdate':echo '是否允许管理员受理报修单前对报修单进行修改。';
                                            break;
                                        default:echo '';
                                    }
                                    ?></div></div>
                        </div>
                        <?php
                    } elseif ($one['type'] == 'checkbox' && $ranges = json_decode($one['store_range'])) {
                        ?>
                        <div class="form-group field-<?= $one['code'] ?>"><label class="col-md-2 control-label"><?= $one['tag'] ?></label>
                            <div class="col-md-4">
                                <div id="<?= $one['code'] ?>">
                                    <?php
                                    $values = explode(',', $one['value']);
                                    foreach ($ranges as $key => $range) {
                                        ?>
                                        <label class="checkbox-inline"><input type="checkbox" name="System[<?= $one['code'] ?>][]" value="<?= $key ?>" <?= in_array("$key", $values) ? 'checked="checked"' : '' ?>> <?= $range ?></label>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-md-6"><div class="help-block">
                                    <img id="imgVerifyCode" height="34"  alt="验证码" style="cursor: pointer;">
                                </div></div>
                        </div>
                        <?php
                    }
                }
                ?>
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
<?php $this->beginBlock('business') ?>
    $(document).ready(function () {
        changeshow();
        $('.radio-business_forum,.radio-business_roomtype').on('change', function () {
            changeshow();
        });

    });
    function changeshow() {
        var forum = $('.radio-business_forum:checked').val();
        var roomtype = $('.radio-business_roomtype:checked').val();
        if (forum === '1') {
            $('.field-business_room').show();
        } else {
            $('.field-business_room').hide();
        }
        if (roomtype === '1') {
            $('.field-business_bed').show();
        } else {
            $('.field-business_bed').hide();
        }
    }

<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['business'], \yii\web\View::POS_END); ?>