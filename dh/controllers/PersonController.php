<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;

/**
 * Person controller
 */
class PersonController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

}
