<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use common\models\User;
use dh\models\Category;

/**
 * Api controller
 */
class AjaxController extends Controller {

    /**
     *
     * @return json
     */
    public function actionChangePlate($id) {
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

    /**
     *
     * @return json
     */
    public function actionChangeSkin($id) {
        $total = isset(Yii::$app->params['skin_total']) ? Yii::$app->params['skin_total'] : [];
        $key = array_search($id, $total);
        $skin = $key === FALSE ? 'default' : $total[($key + 1) % count($total)];
        if (Yii::$app->user->isGuest) {
            $cookie = new Cookie([
                'name' => 'skin',
                'expire' => time() + 86400 * 7,
                'value' => $skin,
                'httpOnly' => true
            ]);

            Yii::$app->response->cookies->add($cookie);
            return json_encode(['stat' => 'success', 'skin' => $skin]);
        } else {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            $user->skin = $skin;
            $result = $user->save();
            if ($result) {
                return json_encode(['stat' => 'success', 'skin' => $skin]);
            } else {
                return json_encode(['stat' => 'fail']);
            }
        }
    }

    /**
     *
     * @return json
     */
    public function actionCategoryCollect($id) {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        } else {
            $model = new Category();
            $model->uid = Yii::$app->user->identity->id;
            $model->title = Category::findTitle($id);
            $model->sort_order = Category::findMaxSort($model->uid) + 1;
            $model->is_open = Category::ISOPEN_OPEN;
            $model->stat = Category::STAT_OPEN;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();

                $transaction->commit();
                return json_encode(['stat' => 'success']);
            } catch (\Exception $e) {

                $transaction->rollBack();
                //throw $e;
                //Yii::$app->session->setFlash('error', '创建失败。');
                return json_encode(['stat' => 'fail']);
            }
        }
    }

}
