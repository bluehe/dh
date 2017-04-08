<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '完善信息';

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
?>

<div class="login-box">
    <div class="login-logo">
        <?=
        Html::a('<b>' . Yii::$app->name . '</b>', Yii::$app->homeUrl)
        ?>

    </div>
    <!-- /.login-logo -->
    <div class="nav-tabs-custom complete">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">绑定已有帐号</a></li>
            <li><a href="#tab_2" data-toggle="tab">创建帐号并绑定</a></li>


        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="login-box-body">


                    <?php $form1 = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

                    <?=
                            $form1
                            ->field($model_l, 'username', $fieldOptions1)
                            ->label(false)
                            ->textInput(['placeholder' => $model_l->getAttributeLabel('username')])
                    ?>

                    <?=
                            $form1
                            ->field($model_l, 'password', $fieldOptions2)
                            ->label(false)
                            ->passwordInput(['placeholder' => $model_l->getAttributeLabel('password')])
                    ?>
                    <?php if ($model_l->scenario == 'captchaRequired'): ?>
                        <?=
                        $form1->field($model_l, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-xs-8">{input}</div><div class="col-xs-4">{image}</div></div>',
                            'options' => ['placeholder' => $model_l->getAttributeLabel('verifyCode'), 'class' => 'form-control', 'autoCompete' => false],
                            'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图', 'style' => 'cursor:pointer', 'height' => 34]])->label(false)
                        ?>
                    <?php endif; ?>
                    <div class="row">

                        <!-- /.col -->
                        <div class="col-xs-12">
                            <?= Html::submitButton('绑 定', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'type', 'value' => 'bind']) ?>
                        </div>
                        <!-- /.col -->
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <div class="login-box-body">


                    <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableAjaxValidation' => true, 'enableClientValidation' => true]); ?>

                    <?=
                            $form
                            ->field($model_s, 'username', $fieldOptions1)
                            ->label(false)
                            ->textInput(['placeholder' => $model_s->getAttributeLabel('username')])
                    ?>
                    <?=
                            $form
                            ->field($model_s, 'email', $fieldOptions2)
                            ->label(false)
                            ->textInput(['placeholder' => $model_s->getAttributeLabel('email')])
                    ?>

                    <?=
                            $form
                            ->field($model_s, 'password', $fieldOptions3)
                            ->label(false)
                            ->passwordInput(['placeholder' => $model_s->getAttributeLabel('password')])
                    ?>
                    <?=
                            $form
                            ->field($model_s, 'password1', $fieldOptions3)
                            ->label(false)
                            ->passwordInput(['placeholder' => $model_s->getAttributeLabel('password1')])
                    ?>
                    <?php if ($model_s->scenario == 'captchaRequired'): ?>
                        <?=
                        $form->field($model_s, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-xs-8">{input}</div><div class="col-xs-4">{image}</div></div>',
                            'options' => ['placeholder' => $model_s->getAttributeLabel('verifyCode'), 'class' => 'form-control', 'autoCompete' => false],
                            'imageOptions' => ['alt' => '点击换图', 'title' => '点击换图', 'style' => 'cursor:pointer', 'height' => 34]])->label(false)
                        ?>
                    <?php endif; ?>

                    <div class="row">

                        <div class="col-xs-12">
                            <?= Html::submitButton('创建帐号并绑定', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'type', 'value' => 'new']) ?>
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
            </div>
            <!-- /.tab-pane -->

        </div>
        <!-- /.tab-content -->
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
