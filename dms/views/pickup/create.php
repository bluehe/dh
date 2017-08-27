<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model dms\models\Pickup */

$this->title = '创建Pickup';
$this->params['breadcrumbs'][] = ['label' => 'Pickups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
