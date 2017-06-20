<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */

$this->title = '发布信息';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['business/repair-business']];
$this->params['breadcrumbs'][] = ['label' => '拾物招领', 'url' => ['business/pickup-business']];
?>
<div class="pickup-update">

    <?=
    $this->render('_form-pickup', [
        'model' => $model,
    ])
    ?>

</div>
