<?php
/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '添加类型';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = ['label' => '类型管理', 'url' => ['repair/type']];
?>
<div class="type-create">

    <?=
    $this->render('_form-type', [
        'model' => $model,
    ])
    ?>

</div>
