<?php
/* @var $this yii\web\View */
/* @var $model dms\models\RepairUnit */

$this->title = '添加单位';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = ['label' => '单位管理', 'url' => ['repair/unit']];
?>
<div class="repair-unit-create">

    <?=
    $this->render('_form-unit', [
        'model' => $model,
    ])
    ?>

</div>
