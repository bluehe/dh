<?php
/* @var $this yii\web\View */
/* @var $model dh\models\UserLevel */

$this->title = '推荐网址';
$this->params['breadcrumbs'][] = ['label' => '业务中心', 'url' => ['suggest-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recommend-update">

    <?=
    $this->render('_form-recommend', [
        'model' => $model,
        'maxsize' => $maxsize,
    ])
    ?>

</div>
