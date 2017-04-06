<?php
/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '添加楼层';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '楼层设置', 'url' => ['forum/floor']];
?>
<div class="floor-create">

    <?=
    $this->render('_form-floor', [
        'model' => $model,
    ])
    ?>

</div>
