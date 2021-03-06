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
                    $file = @file_get_contents('image/default_e.png');
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

    public function actionAddurl($url, $title) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        } else {
            $cates = Category::get_category_sql(Yii::$app->user->identity->id)->asArray()->all();
            if (count($cates) > 0) {
                foreach ($cates as $key => $cate) {
                    $websites = Website::get_website(NULL, $cate['id']);
                    $cates[$key]['website'] = $websites;
                }
            }
            $common = Website::get_website_order(10, Yii::$app->user->identity->id);

            return $this->render('site/user', ['cates' => $cates, 'common' => $common]);
        }
    }

    public function actionWebhooks() {
        // GitHub Webhook Secret
        // Keep it the same with the 'Secret' field on your Webhooks / Manage webhook page of your respostory.
        $secret = "";
        // 项目根目录, 如: "/var/www/fizzday"
        $path = "/data/wwwroot/dms";
        // Headers deliveried from GitHub
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
        if ($signature) {
            $hash = "sha1=" . hash_hmac('sha1', $HTTP_RAW_POST_DATA, $secret);
            if (strcmp($signature, $hash) == 0) {
                echo shell_exec("cd {$path} && git pull");
                exit();
            }
        }
        http_response_code(404);
    }

    public function actionQrcode() {
        return Qrcode::png("baidu.com");
    }

}
