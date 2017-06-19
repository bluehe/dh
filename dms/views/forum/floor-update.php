<?php
//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '更新楼层: ' . $model->v;
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '楼层管理', 'url' => ['forum/floor']];
?>
<div class="floor-update">

    <?=
    $this->render('_form-floor', [
        'model' => $model,
    ])
    ?>

</div>
