<?php
//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '更新年级: ' . $model->v;
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['college/college']];
$this->params['breadcrumbs'][] = ['label' => '年级管理', 'url' => ['college/grade']];
?>
<div class="grade-update">

    <?=
    $this->render('_form-grade', [
        'model' => $model,
    ])
    ?>

</div>
