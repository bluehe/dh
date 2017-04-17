<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Bed */

$this->title = '更新床位: ' . $model->forum->name . $model->floor->v . ($model->room->rid ? $model->room->fname . '-' . $model->room->name : $model->room->name) . '-' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '床位管理', 'url' => ['forum/bed']];
?>
<div class="bed-update">

    <?=
    $this->render('_form-bed', [
        'model' => $model,
    ])
    ?>

</div>
