<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\Pickup */

$this->title = '更新Pickup: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pickups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="pickup-update">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
