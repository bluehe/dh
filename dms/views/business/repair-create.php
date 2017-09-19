<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */

$this->title = '我要报修';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['business/repair-business']];
$this->params['breadcrumbs'][] = ['label' => '网上报修', 'url' => ['business/repair-business']];
?>
<div class="repair-order-create">

    <?=
    $this->render('_form-repair', [
        'model' => $model,
        'maxsize' => $maxsize,
    ])
    ?>

</div>
