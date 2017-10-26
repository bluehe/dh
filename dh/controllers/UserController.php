<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;

/**
 * User controller
 */
class UserController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

}
