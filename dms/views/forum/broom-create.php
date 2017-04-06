<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\Broom */

$this->title = '创建房间';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '房间管理', 'url' => ['forum/broom']];
?>
<div class="broom-create">

    <?=
    $this->render('_form-broom', [
        'model' => $model,
    ])
    ?>

</div>
