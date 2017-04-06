<?php
//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '更新年级: ' . $model->v;
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = ['label' => '年级设置', 'url' => ['common/grade']];
?>
<div class="grade-update">

    <?=
    $this->render('_form-grade', [
        'model' => $model,
    ])
    ?>

</div>
