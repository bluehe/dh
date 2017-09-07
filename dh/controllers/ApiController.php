<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use dh\models\Website;

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
        session_write_close();

        $url = parse_url(Yii::$app->request->get('url'), PHP_URL_HOST);
        $dir = 'data/icon'; //图标存放文件夹
        if (!is_dir($dir)) {
            mkdir($dir, 0777, TRUE);
        }
        $fav = $dir . "/" . $url . ".png"; //图标存放路径


        if (file_exists($fav)) {
            Website::updateIcon(Yii::$app->request->get('url'), $url . ".png");
            header('Content-type: image/png');
            return $file = @file_get_contents($fav);
        }

        $f1 = @file_get_contents("https://api.byi.pw/favicon/?url=$url");
        @file_put_contents($fav, $f1);

//        $img_info_1 = md5_file("https://api.byi.pw/favicon/?url=$url");
//        $img_info_2 = md5_file("https://api.byi.pw/favicon/?url=error"); //别人接口默认的值

        $size = filesize($fav);
        if ($size == 492 || $size == 0 || $size == 726) {
            @unlink($fav);
            header('Content-type: image/png');
            return @file_get_contents('image/default_ico.png');
        } else {
            Website::updateIcon(Yii::$app->request->get('url'), $url . ".png");
            header('Content-type: image/png');
            return $f1;
        }
    }

}
