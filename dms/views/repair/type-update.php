<?php
//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '更新类型: ' . $model->v;
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = ['label' => '类型管理', 'url' => ['repair/type']];
?>
<div class="type-update">

    <?=
    $this->render('_form-type', [
        'model' => $model,
    ])
    ?>

</div>
