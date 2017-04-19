<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairWorker */

$this->title = '更新人员: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '维修设置', 'url' => ['repair/unit']];
$this->params['breadcrumbs'][] = ['label' => '人员管理', 'url' => ['repair/worker']];
?>
<div class="repair-worker-update">

    <?=
    $this->render('_form-worker', [
        'model' => $model,
    ])
    ?>

</div>
