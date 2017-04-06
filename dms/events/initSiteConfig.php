<?php

namespace dms\events;

use Yii;
use yii\base\Event;
use dms\models\System;

class initSiteConfig extends Event {

    public static function assign() {

        $cache = Yii::$app->cache;
        $smtp = $cache->get('system_smtp');
        if ($smtp === false) {
            $smtp = System::getChildrenValue('smtp');
            $range = System::find()->where(['code' => 'smtp_charset'])->select('store_range')->one();
            $charsets = json_decode($range['store_range'], true);
            $smtp['smtpcharset'] = $charsets[$smtp['smtp_charset']];
            $cache->set('system_smtp', $smtp);
        }
        if ($smtp['smtp_service']) {
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
                    'charset' => $smtp['smtpcharset'], //改变
                    'from' => [$smtp['smtp_from'] => Yii::$app->name]
                ],
            ]);
        }

        return true;
    }

}
