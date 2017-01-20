<?php
/* @var $this yii\web\View */
/* @var $model dms\models\College */

$this->title = '创建学院';
$this->params['breadcrumbs'][] = ['label' => '参数设置', 'url' => ['common/college']];
$this->params['breadcrumbs'][] = ['label' => '学院设置', 'url' => ['common/college']];
?>
<div class="college-create">

    <?=
    $this->render('_form-college', [
        'model' => $model,
    ])
    ?>

</div>
