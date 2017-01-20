<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;
use dms\models\ChangePassword;
use common\models\User;
use yii\imagine\Image;

class AccountController extends Controller {

    //账号信息
    public function actionIndex() {
        return $this->render('index', [
                    'model' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword() {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->change()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
            $model = new ChangePassword();
        }

        return $this->render('change-password', [
                    'model' => $model,
        ]);
    }

    /**
     * 修改头像
     * @return
     */
    public function actionThumb() {
        if (Yii::$app->request->post()) {

            $image = Yii::$app->request->post('image');
            if ($image) {
                $file = Yii::getAlias('@webroot') . $image['url'];
                //图片处理
                Image::crop($file, $image['w'], $image['h'], [$image['x'], $image['y']])->save($file);
                Image::resize($file, 200, 200, true, true)->save($file);
                $file_name = basename($file);
                $targetPath = Yii::getAlias('@webroot') . '/data/avatar';
                if (!file_exists($targetPath)) {
                    @mkdir($targetPath, 0777, true);
                }
                $filename = $targetPath . DIRECTORY_SEPARATOR . $file_name;
                if (@copy($file, $filename)) {
                    //删除临时文件
                    @unlink($file);
                    //删除原来头像
                    @unlink(Yii::getAlias('@webroot') . Yii::$app->user->identity->avatar);
                    //修改数据库
                    $user = User::findIdentity(Yii::$app->user->identity->id);
                    $user->avatar = '/data/avatar/' . $file_name;
                    $user->save(false);
                    Yii::$app->session->setFlash('success', '头像设置成功。');
                } else {
                    Yii::$app->session->setFlash('error', '头像设置失败。');
                }

                return $this->redirect(['account/thumb']);
            } else {
                $url = Yii::$app->request->post('url');
                return $this->renderAjax('_jcrop', ['url' => $url]);
            }
        }
        $postMaxSize = ini_get('post_max_size');
        $fileMaxSize = ini_get('upload_max_filesize');
        $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;
        switch (substr($displayMaxSize, -1)) {
            case 'G':
                $displayMaxSize = $displayMaxSize * 1024 * 1024;
            case 'M':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'K':
                $displayMaxSize = $displayMaxSize;
        }

        return $this->render('thumb', ['user' => Yii::$app->user->identity, 'maxsize' => $displayMaxSize]);
    }

}
