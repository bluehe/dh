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
                    <dt>用户名</dt><dd><?= $model->username ?></dd>
                    <dt>电子邮箱</dt><dd><?= $model->email ?></dd>
                    <dt>密码</dt><dd><a class="btn btn-primary btn-sm" href="<?php echo Yii::$app->urlManager->createUrl(['account/change-password']); ?>">修改密码</a></dd>
                    <dt>注册时间</dt><dd><?= Yii::$app->formatter->asDatetime($model->created_at) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
