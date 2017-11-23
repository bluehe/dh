<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dh\models\Category */

$this->title = '添加分类';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?=
    $this->render('_form-category', [
        'model' => $model,
    ])
    ?>

</div>
