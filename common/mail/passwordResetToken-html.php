<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    Hello, &nbsp; <?= Html::encode($user->username) ?> ，<br />
    这封信是由 【<?= Yii::$app->name ?>】 发送的。<br />
    <br />
    您收到这封邮件，是因为您在我们系统重设密码使用了您的地址。如果您并<br />
    没有访问过我们的系统，或没有进行上述操作，请忽略这封邮件。您不需要<br />
    退订或进行其他进一步的操作。<br />
    <br />
    ----------------------------------------------------------------------<br />
    密码重设说明<br />
    ----------------------------------------------------------------------<br />
    <br />
    您是我们系统的用户，您重设密码时使用了本地址，我们需要对您的身份和<br />
    地址有效性进行验证以避免他人盗取密码或地址被滥用。<br />
    <br />
    您只需点击下面的链接即可重设您的密码：<br />
    <br />
    <?=
    Html::a(Html::encode($resetLink), $resetLink, ['target' => '_blank'])
    ?>
    <br />
    <br />
    (如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)<br />
    <br />
    感谢您的访问，祝您使用愉快！<br />
    <br />
    此致<br />
    <br />
    【<?= Yii::$app->name ?>】 管理团队.<br />
    <?=
    Html::a(Yii::$app->name, Yii::$app->urlManager->createAbsoluteUrl(['site/index']), ['target' => '_blank'])
    ?>
</div>
