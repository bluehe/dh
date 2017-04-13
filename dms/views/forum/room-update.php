<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Broom */

$this->title = '更新房间: ' . $model->forums->name . $model->floors->v . ($model->rid ? $model->parent->name . '-' . $model->name : $model->name);
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['forum/room']];
?>
<div class="room-update">

    <?=
    $this->render('_form-room', [
        'model' => $model,
    ])
    ?>

</div>
