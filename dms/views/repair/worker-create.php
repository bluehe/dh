<?php
/* @var $this yii\web\View */
/* @var $model dms\models\RepairWorker */

$this->title = '添加人员';
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = ['label' => '人员管理', 'url' => ['repair/worker']];
?>
<div class="repair-worker-create">

<?=
$this->render('_form-worker', [
    'model' => $model,
])
?>

</div>
