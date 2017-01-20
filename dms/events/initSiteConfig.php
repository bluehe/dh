<?php

namespace dms\events;

use Yii;
use yii\base\Event;
use dms\models\System;

class initSiteConfig extends Event {

    public static function assign() {
        if (System::getValue('smtp_service')) {
            $smtp = System::getChildrenValue('smtp');
            $range = System::find()->where(['code' => 'smtp_charset'])->select('store_range')->one();
            $charsets = json_decode($range['store_range'], true);
            Yii::$app->set('mailer', [
                'class' => 'yii\swiftmailer\Mailer',
                'viewPath' => '@common/mail',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => $smtp['smtp_host'],
                    'username' => $smtp['smtp_username'],
                    'password' => $smtp['smtp_password'],
                    'port' => $smtp['smtp_port'],
                    'encryption' => $smtp['smtp_ssl'] ? 'ssl' : 'tls',
                ],
                'messageConfig' => [
                    'charset' => $charsets[$smtp['smtp_charset']],
                    'from' => [$smtp['smtp_from'] => Yii::$app->name]
                ],
            ]);
        }
        return true;
    }

}
