<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;

class WechatController extends Controller {

    public function actionIndex() {
        $wechat = Yii::$app->wechat;
        return $wechat->getUserList();
    }

}
