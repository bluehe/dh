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
                            <div class="col-md-6"><div class="help-block"></div></div>
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