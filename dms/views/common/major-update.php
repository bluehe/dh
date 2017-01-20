<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\Major */

$this->title = '更新专业: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = ['label' => '专业设置', 'url' => ['common/major']];
?>
<div class="major-update">

    <?=
    $this->render('_form-major', [
        'model' => $model,
    ])
    ?>

</div>
