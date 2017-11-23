<?php

use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '注册信息';
$this->params['breadcrumbs'][] = ['label' => '账号信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>ID</dt><dd><?= $model->id ?></dd>
                    <dt>用户名</dt><dd><?= $model->username ?></dd>
                    <dt>密码</dt><dd><a class="btn btn-primary btn-xs" href="<?php echo Yii::$app->urlManager->createUrl(['account/change-password']); ?>">修改密码</a></dd>
                    <dt>昵称</dt><dd><span class="nickname"><?= $model->nickname ?></span> <a class="btn btn-primary btn-xs change-nickname" href="javascript:void(0);">修改昵称</a></dd>
                    <dt>注册时间</dt><dd><?= Yii::$app->formatter->asDatetime($model->created_at) ?></dd>
                    <dt>电子邮箱</dt><dd><?= $model->email ?></dd>
                    <dt>联系电话</dt><dd><?= $model->tel ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'account-modal',
    'header' => '<h4 class="modal-title"></h4>',
    'options' => [
        'tabindex' => false
    ],
]);
Modal::end();
?>
<script>
<?php $this->beginBlock('user') ?>
    $('.change-nickname').on('click', function () {
        $.get('<?= Url::toRoute('ajax-back/change-nickname') ?>',
                function (data) {
                    $('#account-modal .modal-title').html('修改昵称');
                    $('#account-modal .modal-body').html(data);
                    $('#account-modal').modal('show');
                }
        );
    });
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['user'], \yii\web\View::POS_END); ?>
