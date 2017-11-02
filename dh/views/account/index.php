<?php
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
                    <dt>昵称</dt><dd><?= $model->nickname ?> <a class="btn btn-primary btn-xs" href="<?php echo Yii::$app->urlManager->createUrl(['account/change-nickname']); ?>">修改昵称</a></dd>
                    <dt>注册时间</dt><dd><?= Yii::$app->formatter->asDatetime($model->created_at) ?></dd>
                    <dt>电子邮箱</dt><dd><?= $model->email ?></dd>
                    <dt>联系电话</dt><dd><?= $model->tel ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
