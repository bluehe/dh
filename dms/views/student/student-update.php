<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Student */

$this->title = '更新学生: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['student/student']];
$this->params['breadcrumbs'][] = ['label' => '学生管理', 'url' => ['student/student']];
?>
<div class="student-update">

    <?=
    $this->render('_form-student', [
        'model' => $model,
    ])
    ?>

</div>
