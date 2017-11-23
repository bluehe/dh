<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use dh\models\User;

/**
 * Api controller
 */
class AjaxBackController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionChangeNickname() {

        if (!Yii::$app->user->isGuest) {
            $model = User::findOne(Yii::$app->user->identity->id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return json_encode(['stat' => 'success', 'nickname' => $model->nickname]);
            } else {
                return $this->renderAjax('change-nickname', [
                            'model' => $model,
                ]);
            }
        } else {
            return false;
        }
    }

}
