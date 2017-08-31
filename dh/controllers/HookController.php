<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class HookController extends Controller {


    /**
     * 自动pull
     */
    public function actionGit() {
        $token = 'my_dms';
        //获取http 头
        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['token']) || $json['token'] !== $token) {
            echo 'error request';
        } else {
            //调用shell
            echo exec("/data/wwwroot/dms/git.sh");
        }
    }

}
