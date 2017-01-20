<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Major */

$this->title = '创建专业 ';
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = ['label' => '专业设置', 'url' => ['common/major']];
?>
<div class="major-create">

    <?=
    $this->render('_form-major', [
        'model' => $model,
    ])
    ?>

</div>
