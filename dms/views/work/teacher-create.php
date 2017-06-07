<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Teacher */

$this->title = '添加教职工';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['work/teacher']];
$this->params['breadcrumbs'][] = ['label' => '教职工管理', 'url' => ['work/teacher']];
?>
<div class="teacher-create">

    <?=
    $this->render('_form-teacher', [
        'model' => $model,
    ])
    ?>

</div>
