<?php
/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '添加年级';
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['college/college']];
$this->params['breadcrumbs'][] = ['label' => '年级管理', 'url' => ['college/grade']];
?>
<div class="grade-create">

    <?=
    $this->render('_form-grade', [
        'model' => $model,
    ])
    ?>

</div>
