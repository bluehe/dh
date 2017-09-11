<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use common\models\User;
use dh\models\Category;
use dh\models\Website;

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
            $model = Category::findOne($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $cate = new Category();
                $cate->title = $model->title;
                $cate->uid = Yii::$app->user->identity->id;
                $cate->sort_order = Category::findMaxSort(Yii::$app->user->identity->id) + 1;
                $cate->is_open = Category::ISOPEN_OPEN;
                $cate->stat = Category::STAT_OPEN;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $cate->save(false);

                    $query = Website::find()->where(['cid' => $id, 'stat' => Website::STAT_OPEN, 'is_open' => Website::ISOPEN_OPEN]);
                    foreach ($query->each() as $website) {
                        $w = new Website();
                        $w->loadDefaultValues();
                        $w->cid = $cate->id;
                        $w->title = $website->title;
                        $w->url = $website->url;
                        $w->sort_order = Website::findMaxSort($cate->id) + 1;
                        $w->save(false);
                        $website->collect_num += 1;
                        $website->save(false);
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', '22');
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {

                    $transaction->rollBack();
                    //throw $e;

                    return json_encode(['stat' => 'fail']);
                }
            } else {
                return $this->renderAjax('_form-category', [
                            'model' => $model,
                ]);
            }
        }
    }

}
