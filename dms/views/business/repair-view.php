<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use dms\models\RepairOrder;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */
?>
<div class="repair-order-view">


    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'serial',
            'created_at:datetime',
            ['label' => $model->getAttributeLabel('repair_type'), 'value' => $model->repair_type ? $model->type->v : $model->repair_type],
            'repair_area',
            'address',
            //'title',
            'content',
            ['label' => $model->getAttributeLabel('evaluate'), 'value' => $model->evaluate, 'visible' => false],
//            'accept_at',
//            'accept_uid',
//            'repair_at',
//            'repair_uid',
//            'worker_id',
            ['label' => $model->getAttributeLabel('end_at'), 'value' => $model->end_at, "format" => ["date", "php:Y-m-d H:i:s"], 'visible' => $model->stat == RepairOrder::STAT_CLOSE],
//            'note',
            ['label' => $model->getAttributeLabel('stat'), 'value' => $model->Stat],
        ],
    ])
    ?>

</div>
