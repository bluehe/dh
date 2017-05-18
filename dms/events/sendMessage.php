<?php

namespace dms\events;

use Yii;
use yii\base\Event;

class sendMessage extends Event {

    public static function index($emailto) {

        $mail = Yii::$app->mailer->compose()
                ->setTo($emailto)
                ->setSubject(Yii::$app->name . '测试邮件')
                ->setTextBody('测试邮件')
                ->setHtmlBody('<b>测试邮件</b>');

        if ($mail->send()) {
            return 'success';
        } else {
            return 'fail';
        }
    }

}
