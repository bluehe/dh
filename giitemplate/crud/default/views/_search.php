<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">
    <div class="box box-info">
        <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>
        <div class="box-body">
            <?php
            $count = 0;
            foreach ($generator->getColumnNames() as $attribute) {
                if (++$count < 6) {
                    echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                } else {
                    echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                }
            }
            ?>
        </div>
        <div class="box-footer">
            <div class="col-lg-1 col-lg-offset-2 col-xs-6 text-right">
                <?= "<?= " ?>Html::submitButton( <?= $generator->generateString('搜索') ?> , ['class' => 'btn btn-primary']) ?>
            </div>
            <div class="col-lg-1 col-xs-6 text-left">
                <?= "<?= " ?>Html::resetButton( <?= $generator->generateString('重置') ?> , ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>
</div>
