<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model dms\models\CheckOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Check Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-order-view">
    <div class="box box-info">
        <div class="box-body">
            <p>
                <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('删除', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
                ],
                ]) ?>
            </p>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'related_table',
            'related_id',
            'bids',
            'note',
            'created_at',
            'updated_at',
            'checkout_at',
            'created_uid',
            'updated_uid',
            'checkout_uid',
            'stat',
            ],
            ]) ?>
        </div>
    </div>
</div>
