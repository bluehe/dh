<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use common\models\User;

/**
 * Api controller
 */
class AjaxController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPlate($id) {
        $total = isset(Yii::$app->params['plate_total']) ? Yii::$app->params['plate_total'] : 3;
        $plate = ($id + 1) % $total;
        if (Yii::$app->user->isGuest) {
            $cookie = new Cookie([
                'name' => 'plate',
                'expire' => time() + 86400 * 7,
                'value' => $plate,
                'httpOnly' => true
            ]);

            Yii::$app->response->cookies->add($cookie);
            return json_encode(['stat' => 'success', 'plate' => $plate]);
        } else {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            $user->plate = $plate;
            $result = $user->save();
            if ($result) {
                return json_encode(['stat' => 'success', 'plate' => $plate]);
            } else {
                return json_encode(['stat' => 'fail']);
            }
        }
    }

}
