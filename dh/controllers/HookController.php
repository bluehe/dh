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

            //调用shell
            exec("/data/wwwroot/dms/git.sh");
    }

}
