<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model dms\models\Pickup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pickups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-view">
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
            'type',
            'uid',
            'name',
            'tel',
            'goods',
            'address',
            'content',
            'created_at',
            'end_at',
            'stat',
            ],
            ]) ?>
        </div>
    </div>
</div>
