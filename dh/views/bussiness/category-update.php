<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dh\models\Category */

$this->title = '更新分类' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-update">

    <?=
    $this->render('_form-category', [
        'model' => $model,
    ])
    ?>

</div>
