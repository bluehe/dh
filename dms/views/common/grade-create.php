<?php
/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '添加年级';
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = ['label' => '年级设置', 'url' => ['common/grade']];
?>
<div class="grade-create">

    <?=
    $this->render('_form-grade', [
        'model' => $model,
    ])
    ?>

</div>
