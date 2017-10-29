<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \dh\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<div class="container bt-1">
    <div class="row">
        <div class="col-lg-7">
            <div class="login-box">

                <!-- /.login-logo -->
                <div class="login-box-body">
                    <p class="login-box-msg">欢迎登录</p>

                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

                    <?=
                            $form
                            ->field($model, 'username', $fieldOptions1)
                            ->label(false)
                            ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                    ?>

                    <?=
                            $form
                            ->field($model, 'password', $fieldOptions2)
                            ->label(false)
                            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
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
                            <?= $form->field($model, 'rememberMe')->checkbox()->label($model->getAttributeLabel('rememberMe')) ?>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <?= Html::submitButton('登 录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                        </div>
                        <!-- /.col -->
                    </div>


                    <?php ActiveForm::end(); ?>
                    <?=
                    Html::a('忘记密码', ['/site/request-password-reset'])
                    ?>
                    <?=
                    Html::a('注册新账号', ['/site/signup'], ['class' => 'pull-right'])
                    ?>

                </div>
            </div>
            <!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <div class="col-lg-5">
            <div class="social-auth-links social-icon">
                <p>第三方账号登录</p>
                <?=
                yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['site/auth'], 'popupMode' => false,])
                ?>
            </div>
        </div>
    </div>
</div>
