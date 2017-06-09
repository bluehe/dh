<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\CheckOrder */

$this->title = '更新Check Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Check Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="check-order-update">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
