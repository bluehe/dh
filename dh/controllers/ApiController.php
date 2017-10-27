<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use dosamigos\qrcode\QrCode;

/**
 * Api controller
 */
class ApiController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionGetfav() {
        $url = Yii::$app->request->get('url'); //parse_url(Yii::$app->request->get('url'), PHP_URL_HOST);
        $cache = Yii::$app->cache;
        $file = $cache->get('url_' . $url);
        if ($file === false) {
            session_write_close();
            $flag = 0;
            $dir = 'data/icon'; //图标存放文件夹
            if (!is_dir($dir)) {
                mkdir($dir, 0777, TRUE);
            }
            $fav = $dir . "/" . $url . ".png"; //图标存放路径
            if (file_exists($fav)) {
                $file = @file_get_contents($fav);
                $flag = 1;
            } else {
                $file = @file_get_contents("https://api.byi.pw/favicon/?url=$url");
                @file_put_contents($fav, $file);
                $flag = 1;

//        $img_info_1 = md5_file("https://api.byi.pw/favicon/?url=$url");
//        $img_info_2 = md5_file("https://api.byi.pw/favicon/?url=error"); //别人接口默认的值

                $size = filesize($fav);
                if ($size == 492 || $size == 0 || $size == 726) {
                    @unlink($fav);
                    $file = @file_get_contents('image/default_ico.png');
                    $flag = 0;
                }
            }
            if ($flag) {
                $cache->set('url_' . $url, $file);
            }
        }
        header('Content-type: image/png');
        return $file;
    }

    public function actionQrcode() {
        return Qrcode::png("baidu.com");
    }

}
