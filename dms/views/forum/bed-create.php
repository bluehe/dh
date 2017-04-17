<?php
/* @var $this yii\web\View */
/* @var $model dms\models\Bed */

$this->title = '创建床位';
$this->params['breadcrumbs'][] = ['label' => '楼苑设置', 'url' => ['forum/forum']];
$this->params['breadcrumbs'][] = ['label' => '床位管理', 'url' => ['forum/bed']];
?>
<div class="bed-create">

    <?=
    $this->render('_form-bed', [
        'model' => $model,
    ])
    ?>

</div>
