<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\forum */

$this->title = '更新楼苑: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '楼苑管理', 'url' => ['forum/forum']];
?>
<div class="forum-update">


    <?=
    $this->render('_form-forum', [
        'model' => $model,
    ])
    ?>

</div>
