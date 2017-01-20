<?php

namespace dms\controllers;

use Yii;
use dms\models\System;
use yii\web\Controller;

/**
 * AjaxController
 */
class AjaxController extends Controller {

    /**
     * 发送测试邮件
     */
    public function actionSendEmail() {
        if (Yii::$app->request->post()) {
            $emailto = Yii::$app->request->post('email');

//            if (System::getValue('smtp_service')) {
//                $smtp = System::getChildrenValue('smtp');
//                $range = System::find()->where(['code' => 'smtp_charset'])->select('store_range')->one();
//                $charsets = json_decode($range['store_range'], true);
//                Yii::$app->set('mailer', [
//                    'class' => 'yii\swiftmailer\Mailer',
////                    'viewPath' => '@common/mail',
//                    'transport' => [
//                        'class' => 'Swift_SmtpTransport',
//                        'host' => $smtp['smtp_host'],
//                        'username' => $smtp['smtp_username'],
//                        'password' => $smtp['smtp_password'],
//                        'port' => $smtp['smtp_port'],
//                        'encryption' => $smtp['smtp_ssl'] ? 'ssl' : 'tls',
//                    ],
//                    'messageConfig' => [
//                        'charset' => $charsets[$smtp['smtp_charset']],
//                        'from' => [$smtp['smtp_from'] => Yii::$app->name]
//                    ],
//                ]);
//            }
            $mail = Yii::$app->mailer->compose()
                    ->setTo($emailto)
                    ->setSubject(Yii::$app->name . '测试邮件')
                    ->setTextBody('测试邮件')
                    ->setHtmlBody('<b>测试邮件</b>');

            if ($mail->send()) {
                echo 'success';
            } else {
                echo 'fail';
            }
        }
    }

}
