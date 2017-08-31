<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class HookController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 自动pull
     */
    public function actionGit() {

//作为接口传输的时候认证的密钥
        $valid_token = 'my_dms';
//调用接口被允许的ip地址
        $valid_ip = array('192.168.1.1', '10.17.10.175', '112.112.112.112');
        $client_token = $_GET['token'];
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $fs = fopen('./auto_hook.log', 'a');
        fwrite($fs, 'Request on [' . date("Y-m-d H:i:s") . '] from [' . $client_ip . ']' . PHP_EOL);
        if ($client_token !== $valid_token) {
            echo "error 10001";
            fwrite($fs, "Invalid token [{$client_token}]" . PHP_EOL);
            exit(0);
        }
        if (!in_array($client_ip, $valid_ip)) {
            echo "error 10002";
            fwrite($fs, "Invalid ip [{$client_ip}]" . PHP_EOL);
            exit(0);
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        fwrite($fs, 'Data: ' . print_r($data, true) . PHP_EOL);
        fwrite($fs, '=======================================================================' . PHP_EOL);
        $fs and fclose($fs);
//这里也可以执行自定义的脚本文件update.sh，脚本内容可以自己定义。
        exec("/data/wwwroot/dms/git.sh");
        //exec("cd  /data/wwwroot/dms;git pull");
        exit;
    }

}
