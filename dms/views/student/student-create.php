<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dms\models\Student */

$this->title = '添加学生';
$this->params['breadcrumbs'][] = ['label' => '日常事务', 'url' => ['student/student']];
$this->params['breadcrumbs'][] = ['label' => '学生管理', 'url' => ['student/student']];
?>
<div class="student-create">

    <?=
    $this->render('_form-student', [
        'model' => $model,
    ])
    ?>

</div>
