<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use dh\models\Suggest;
use dh\models\SuggestSearch;
use dh\models\Recommend;
use yii\imagine\Image;

class BussinessController extends Controller {

    /**
     * Lists all Suggest models.
     * @return mixed
     */
    public function actionSuggestList() {
        $searchModel = new SuggestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('suggest-list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Suggest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSuggestView($id) {
        $model = Suggest::findOne(['id' => $id]);
        if ($model !== null) {
            return $this->renderAjax('/person/suggest-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionSuggestReply($id) {

        $model = Suggest::find()->where(['id' => $id, 'stat' => Suggest::STAT_OPEN])->one();
        $model->setScenario('reply');

        if ($model->load(Yii::$app->request->post())) {
            $model->reply_uid = Yii::$app->user->identity->id;
            $model->stat = Suggest::STAT_REPLY;
            $model->updated_at = time();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('suggest-reply', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /**
     * Lists all Recommend models.
     * @return mixed
     */
    public function actionRecommendList() {
        $dataProvider = new ActiveDataProvider([
            'query' => Recommend::find(),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]],
        ]);

        return $this->render('recommend-list', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRecommendCreate() {
        $model = new Recommend();
        $model->loadDefaultValues();
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
        $maxsize = $displayMaxSize;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['recommend-update', 'id' => $model->id]);
        } else {
            return $this->render('recommend-update', [
                        'model' => $model,
                        'maxsize' => $maxsize,
            ]);
        }
    }

    public function actionRecommendUpdate($id) {
        $model = Recommend::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
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
        $maxsize = $displayMaxSize;
        return $this->render('recommend-update', [
                    'model' => $model,
                    'maxsize' => $maxsize,
        ]);
    }

    public function actionRecommendDelete($id) {
        $model = Recommend::findOne($id);
        if ($model !== null) {
            @unlink(Yii::getAlias('@webroot') . $model->img);
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUploadLogo() {

        //判断是否Ajax
        if (Yii::$app->request->isAjax) {

            if (empty($_FILES['file'])) {
                $postMaxSize = ini_get('post_max_size');
                $fileMaxSize = ini_get('upload_max_filesize');
                $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;

                return json_encode(['error' => '没有文件上传,文件最大为' . $displayMaxSize], JSON_UNESCAPED_UNICODE);
                // or you can throw an exception
            }

            //获得文件夹
            $dir = Yii::$app->request->post('dir', 'tmp');

            //目标文件夹，不存在则创建
            $targetFolder = '/data/' . $dir;
            $targetPath = Yii::getAlias('@webroot') . $targetFolder;
            $targetUrl = Yii::getAlias('@web') . $targetFolder;

            if (!file_exists($targetPath)) {
                @mkdir($targetPath, 0777, true);
            }

            $files = $_FILES['file'];
            // a flag to see if everything is ok
            $success = null;

            // get file names
            $filename = $files['name'];

            // loop and process files

            $ext = explode('.', basename($filename));
            $f_name = md5(uniqid()) . "." . strtolower(array_pop($ext));
            $filename = $targetPath . DIRECTORY_SEPARATOR . $f_name;
            $url = $targetUrl . '/' . $f_name;

            //文件存在则删除
            if (file_exists($filename)) {
                @unlink($filename);
            }
            if (@move_uploaded_file($files['tmp_name'], $filename)) {
                Image::resize($filename, 200, 200, true, true)->save($filename);
                $success = true;
                $paths = $filename;
                $urls = $url;
                $file_names = $f_name;
            } else {
                $success = false;
            }

            if ($success === true) {
                $output = ['urls' => $urls, 'paths' => $paths, 'file_names' => $file_names];
            } elseif ($success === false) {
                $output = ['error' => '文件上传出错！'];
                // delete any uploaded files

                @unlink($file);
            }
            return json_encode($output, JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['error' => '上传错误！'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actionLogoCrop() {
        $image = Yii::$app->request->post('image');
        if ($image) {
            $file = Yii::getAlias('@webroot') . $image['url'];
            //图片处理
            Image::crop($file, $image['w'], $image['h'], [$image['x'], $image['y']])->save($file);
            Image::resize($file, 65, 65, true, true)->save($file);
            $file_name = basename($file);
            $targetPath = Yii::getAlias('@webroot') . '/data/recommend';
            if (!file_exists($targetPath)) {
                @mkdir($targetPath, 0777, true);
            }
            $filename = $targetPath . DIRECTORY_SEPARATOR . $file_name;
            if (@copy($file, $filename)) {
                //删除临时文件
                @unlink($file);

                return json_encode(['stat' => 'success', 'file' => '/data/recommend/' . $file_name]);
            } else {
                return json_encode(['stat' => 'fail']);
            }
        } else {
            $url = Yii::$app->request->post('url');
            return $this->renderAjax('_jcrop', ['url' => $url]);
        }
    }

}
