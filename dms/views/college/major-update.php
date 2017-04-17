<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Major */

$this->title = '更新专业: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['college/college']];
$this->params['breadcrumbs'][] = ['label' => '专业管理', 'url' => ['college/major']];
?>
<div class="major-update">

<?=
$this->render('_form-major', [
    'model' => $model,
])
?>

</div>
