<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <?= "<?php " ?>$form = ActiveForm::begin(['id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form',
            'options' => [ 'class' => 'form-horizontal'],
            'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-2 control-label'],
            ],
            ]); ?>
            <div class="box-body">
                <?php
                foreach ($generator->getColumnNames() as $attribute) {
                    if (in_array($attribute, $safeAttributes)) {
                        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
                    }
                }
                ?>
            </div>
            <div class="box-footer">
                <div class="col-md-1 col-lg-offset-2 col-xs-6 text-right">

                    <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('创建') ?> : <?= $generator->generateString('更新') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                </div>
                <div class="col-md-1 col-xs-6 text-left">
                    <?= "<?= " ?>Html::resetButton( <?= $generator->generateString('重置') ?> , ['class' => 'btn btn-default']) ?>
                </div>

            </div>
            <?= "<?php " ?>ActiveForm::end(); ?>

        </div>
    </div>
</div>
