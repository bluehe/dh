<?php
//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '更新学院: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['college/college']];
$this->params['breadcrumbs'][] = ['label' => '学院管理', 'url' => ['college/college']];
?>
<div class="college-update">

    <?=
    $this->render('_form-college', [
        'model' => $model,
    ])
    ?>

</div>
