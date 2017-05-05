<?php

namespace dms\controllers;

use Yii;
use dms\models\System;
use dms\models\Crontab;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

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
                Yii::$app->cache->delete('system_info');
                Yii::$app->session->setFlash('success', '更新成功。');
            } elseif ($res === false) {
                Yii::$app->session->setFlash('error', '更新失败。');
            }
        }

        return $this->render('index', [
                    'model' => System::getChildren('system'),
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
     * 业务设置
     */
    public function actionBusiness() {

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

        return $this->render('business', [
                    'model' => System::getChildren('business'),
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

    /**
     * Lists all Crontab models.
     * @return mixed
     */
    public function actionCrontab() {

        $dataProvider = new ActiveDataProvider([
            'query' => Crontab::find(),
        ]);

        return $this->render('crontab', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Crontab model.
     * If creation is successful, the browser will be redirected to the 'crontab' page.
     * @return mixed
     */
    public function actionCrontabCreate() {
        $model = new Crontab();

        $model->stat = Crontab::STAT_OPEN;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $sql = "CREATE EVENT `{$model->name}` ON SCHEDULE";
                if ($model->interval_time) {
                    $sql .= " EVERY " . $model->getInterval($model->interval_time) . " STARTS '{$model->start_at}'";
                    if ($model->end_at) {
                        $sql .= " ENDS '{$model->end_at}'";
                    }
                } else {
                    $sql .= " AT '{$model->start_at}'";
                }
                $sql .= $model->stat == Crontab::STAT_OPEN ? " ENABLE " : " DISABLE ";
                $sql .= "
                        DO
                        BEGIN
                            {$model->content};
                            UPDATE {{%crontab}} SET exc_at=unix_timestamp(now()) WHERE name='{$model->name}';
                        END";

                Yii::$app->db->createCommand("DROP EVENT IF EXISTS `{$model->name}`;")->execute();
                Yii::$app->db->createCommand($sql)->execute();

                $model->start_at = strtotime($model->start_at);
                if ($model->end_at) {
                    $model->end_at = strtotime($model->end_at);
                }
                if (!$model->save()) {
                    throw new \Exception("操作失败");
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', '添加成功。');
                return $this->redirect(['crontab-update', 'id' => $model->id]);
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '添加失败。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            return $this->render('crontab-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Crontab model.
     * If update is successful, the browser will be redirected to the 'cronteb-update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCrontabUpdate($id) {
        $model = Crontab::findOne($id);

        if ($model !== null) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $sql = "CREATE EVENT `{$model->name}` ON SCHEDULE";
                    if ($model->interval_time) {
                        $sql .= " EVERY " . $model->getInterval($model->interval_time) . " STARTS '{$model->start_at}'";
                        if ($model->end_at) {
                            $sql .= " ENDS '{$model->end_at}'";
                        }
                    } else {
                        $sql .= " AT '{$model->start_at}'";
                    }
                    $sql .= $model->stat == Crontab::STAT_OPEN ? " ENABLE " : " DISABLE ";
                    $sql .= "
                        DO
                        BEGIN
                            {$model->content};
                            UPDATE {{%crontab}} SET exc_at=unix_timestamp(now()) WHERE name='{$model->name}';
                        END";

                    Yii::$app->db->createCommand("DROP EVENT IF EXISTS `{$model->name}`;")->execute();
                    Yii::$app->db->createCommand($sql)->execute();

                    $model->start_at = strtotime($model->start_at);
                    if ($model->end_at) {
                        $model->end_at = strtotime($model->end_at);
                    }
                    if (!$model->save()) {
                        throw new \Exception("操作失败");
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '操作成功。');
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '操作失败。');
                }
            }
            $model->start_at = $model->start_at ? date('Y-m-d H:i:s', $model->start_at) : null;
            $model->end_at = $model->end_at ? date('Y-m-d H:i:s', $model->end_at) : null;
            return $this->render('crontab-update', [
                        'model' => $model,
            ]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCrontabDelete($id) {
        $model = Crontab::findOne($id);

        if ($model !== null) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Yii::$app->db->createCommand("DROP EVENT IF EXISTS `{$model->name}`;")->execute();
                $model->delete();
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
