<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Major */

$this->title = '创建专业 ';
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['college/college']];
$this->params['breadcrumbs'][] = ['label' => '专业管理', 'url' => ['college/major']];
?>
<div class="major-create">

    <?=
    $this->render('_form-major', [
        'model' => $model,
    ])
    ?>

</div>
