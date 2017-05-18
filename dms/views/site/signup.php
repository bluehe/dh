<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '注册';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];
$fieldOptions3 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
$fieldOptions4 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-phone form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <?=
        Html::a('<b>' . Yii::$app->name . '</b>', Yii::$app->homeUrl)
        ?>

    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎注册</p>

        <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableAjaxValidation' => true, 'enableClientValidation' => true]); ?>

        <?=
                $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')])
        ?>
        <?=
                $form
                ->field($model, 'password', $fieldOptions3)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
        ?>
        <?=
                $form
                ->field($model, 'password1', $fieldOptions3)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password1')])
        ?>
        <?=
                $form
                ->field($model, 'email', $fieldOptions2)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('email')])
        ?>

        <?=
                $form
                ->field($model, 'tel', $fieldOptions4)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('tel')])
        ?>


        <?php if ($model->scenario == 'captchaRequired'): ?>
            <?=
            $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-xs-8">{input}</div><div class="col-xs-4">{image}</div></div>',
                'options' => ['placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control', 'autoCompete' => false],
                'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图', 'style' => 'cursor:pointer', 'height' => 34]])->label(false)
            ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-8">
                <?=
                Html::a('立即登录', ['/site/login'])
                ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('注 册', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button     ']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>
        <!--
                <div class="social-auth-links text-center">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                        using Facebook</a>
                    <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign
                        in using Google+</a>
                </div>-->
        <!-- /.social-auth-links -->



    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
