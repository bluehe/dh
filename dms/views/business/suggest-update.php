<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\RepairOrder */

$this->title = '发布信息';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['business/repair-business']];
$this->params['breadcrumbs'][] = ['label' => '投诉建议', 'url' => ['business/suggest-business']];
?>
<div class="suggest-update">

    <?=
    $this->render('_form-suggest', [
        'model' => $model,
    ])
    ?>

</div>
