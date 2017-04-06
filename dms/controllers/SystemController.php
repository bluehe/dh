<?php

namespace dms\controllers;

use Yii;
//use yii\base\Model;
use dms\models\System;
use yii\web\Controller;

/**
 * SystemController
 */
class SystemController extends Controller {

    /**
     * 系统信息

     */
    public function actionIndex() {
//        $settings = System::find()->where(['parent_id' => 1])->indexBy('id')->all();
//        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
//            $res = 0;
//            foreach ($settings as $setting) {
//                $r = $setting->save(false);
//            }
//        }
//        return $this->render('index', ['settings' => $settings]);


        if (Yii::$app->request->post()) {
            $system = Yii::$app->request->post('System');
            $res = 0;
            foreach ($system as $key => $value) {
                $r = System::setValue($key, $value);
                if ($r) {
                    $res++;
                } elseif ($r === false) {
                    $res = false;
                    break;
                }
            }
            if ($res) {
                Yii::$app->session->setFlash('success', '更新成功。');
            } elseif ($res === false) {
                Yii::$app->session->setFlash('error', '更新失败。');
            }
        }

        return $this->render('index', [
                    'model' => System::getChildren('system_info'),
        ]);
    }

    /**
     * 邮件设置
     */
    public function actionSmtp() {

        if (Yii::$app->request->post()) {
            $system = Yii::$app->request->post('System');

            $res = 0;
            foreach ($system as $key => $value) {
                $r = System::setValue($key, $value);
                if ($r) {
                    $res++;
                } elseif ($r === false) {
                    $res = false;
                    break;
                }
            }
            if ($res) {
                Yii::$app->cache->delete('system_smtp');
                Yii::$app->session->setFlash('success', '更新成功。');
            } elseif ($res === false) {
                Yii::$app->session->setFlash('error', '更新失败。');
            }
        }

        return $this->render('smtp', [
                    'model' => System::getChildren('smtp'),
        ]);
    }

    /**
     * 验证码设置
     */
    public function actionCaptcha() {

        if (Yii::$app->request->post()) {
            $system = Yii::$app->request->post('System');
            $system['captcha_open'] = isset($system['captcha_open']) ? implode(',', $system['captcha_open']) : '';
            $res = 0;
            foreach ($system as $key => $value) {
                $r = System::setValue($key, $value);
                if ($r) {
                    $res++;
                } elseif ($r === false) {
                    $res = false;
                    break;
                }
            }
            if ($res) {
                Yii::$app->session->setFlash('success', '更新成功。');
            } elseif ($res === false) {
                Yii::$app->session->setFlash('error', '更新失败。');
            }
        }

        return $this->render('captcha', [
                    'model' => System::getChildren('captcha'),
        ]);
    }

    /**
     * 发送测试邮件
     */
    public function actionSendEmail() {
        if (Yii::$app->request->post()) {
            $emailto = Yii::$app->request->post('email');
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

}
