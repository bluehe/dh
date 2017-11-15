<?php
/* @var $this yii\web\View */
/* @var $model dh\models\UserLevel */

$this->title = '更新等级';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['users']];
$this->params['breadcrumbs'][] = ['label' => '用户等级', 'url' => ['level']];
?>
<div class="user-level-update">

    <?=
    $this->render('_form-level', [
        'model' => $model,
    ])
    ?>

</div>
