<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model dms\models\CheckOrder */

$this->title = '创建Check Order';
$this->params['breadcrumbs'][] = ['label' => 'Check Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-order-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
