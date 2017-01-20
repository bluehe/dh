<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;

/**
 * AjaxController
 */
class UploadController extends Controller {
    /*
     * ajax方式文件上传
     * 输入$_FILES['files']和dir
     * @return json_encode(['error'=>'']）
     */

    public function actionIndex() {

        //判断是否Ajax
        if (Yii::$app->request->isAjax) {

            if (empty($_FILES['files'])) {
                $postMaxSize = ini_get('post_max_size');
                $fileMaxSize = ini_get('upload_max_filesize');
                $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;

                echo json_encode(['error' => '没有文件上传,文件最大为' . $displayMaxSize], JSON_UNESCAPED_UNICODE);
                // or you can throw an exception
                return; // terminate
            }

            //获得文件夹
            $dir = Yii::$app->request->post('dir') ? Yii::$app->request->post('dir') : 'tmp';

            //目标文件夹，不存在则创建
            $targetFolder = '/data/' . $dir;
            $targetPath = Yii::getAlias('@webroot') . $targetFolder;
            $targetUrl = Yii::getAlias('@web') . $targetFolder;

            if (!file_exists($targetPath)) {
                @mkdir($targetPath, 0777, true);
            }

            $files = $_FILES['files'];
            // a flag to see if everything is ok
            $success = null;

            // file paths to store
            $paths = [];
            //访问路径
            $urls = [];
            //文件名
            $file_names = [];

            // get file names
            $filenames = $files['name'];

            // loop and process files
            for ($i = 0; $i < count($filenames); $i++) {
                $ext = explode('.', basename($filenames[$i]));
                $f_name = md5(uniqid()) . "." . strtolower(array_pop($ext));
                $filename = $targetPath . DIRECTORY_SEPARATOR . $f_name;
                $url = $targetUrl . '/' . $f_name;

                //文件存在则删除
                if (file_exists($filename)) {
                    @unlink($filename);
                }
                if (@move_uploaded_file($files['tmp_name'][$i], $filename)) {
                    $success = true;
                    $paths[] = $filename;
                    $urls[] = $url;
                    $file_names[] = $f_name;
                } else {
                    $success = false;
                    break;
                }
            }
            if ($success === true) {
                $output = [ 'urls' => $urls, 'paths' => $paths, 'file_names' => $file_names];
            } elseif ($success === false) {
                $output = ['error' => '文件上传出错！'];
                // delete any uploaded files
                foreach ($paths as $file) {
                    @unlink($file);
                }
            }
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => '上传错误！'], JSON_UNESCAPED_UNICODE);
        }
    }

}
