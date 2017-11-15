<?php
/* @var $this yii\web\View */
/* @var $model dh\models\UserLevel */

$this->title = '添加等级';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-level-create">

    <?=
    $this->render('_form-level', [
        'model' => $model,
    ])
    ?>

</div>
