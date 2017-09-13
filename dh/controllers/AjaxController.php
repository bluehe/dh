<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use common\models\User;
use dh\models\Category;
use dh\models\Website;
use dh\models\Recommend;

/**
 * Api controller
 */
class AjaxController extends Controller {

    /**
     * 改变板式
     * @return json
     */
    public function actionChangePlate($id) {
        $total = isset(Yii::$app->params['plate_total']) ? Yii::$app->params['plate_total'] : 3;
        $plate = ($id + 1) % $total;
        $cookie = new Cookie([
            'name' => 'plate',
            'expire' => time() + 86400 * 7,
            'value' => $plate,
            'httpOnly' => true
        ]);

        Yii::$app->response->cookies->add($cookie);
        if (Yii::$app->user->isGuest) {
            $result = true;
        } else {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            $user->plate = $plate;
            $result = $user->save();
        }
        if ($result) {
            return json_encode(['stat' => 'success', 'plate' => $plate]);
        } else {
            return json_encode(['stat' => 'fail']);
        }
    }

    /**
     * 改变皮肤
     * @return json
     */
    public function actionChangeSkin($id) {
        $total = isset(Yii::$app->params['skin_total']) ? Yii::$app->params['skin_total'] : [];
        $key = array_search($id, $total);
        $skin = $key === FALSE ? 'default' : $total[($key + 1) % count($total)];
        $cookie = new Cookie([
            'name' => 'skin',
            'expire' => time() + 86400 * 7,
            'value' => $skin,
            'httpOnly' => true
        ]);

        Yii::$app->response->cookies->add($cookie);
        if (Yii::$app->user->isGuest) {

            $result = true;
        } else {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            $user->skin = $skin;
            $result = $user->save();
        }
        if ($result) {
            return json_encode(['stat' => 'success', 'skin' => $skin]);
        } else {
            return json_encode(['stat' => 'fail']);
        }
    }

    /**
     * 收藏分类
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
                        $website->updateCounters(['collect_num' => 1]);
                    }
                    $model->updateCounters(['collect_num' => 1]);

                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {

                    $transaction->rollBack();
                    //throw $e;

                    return json_encode(['stat' => 'fail']);
                }
            } else {
                return $this->renderAjax('category-collect', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * 收藏网址
     * @return json
     */
    public function actionWebsiteCollect($id) {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        } else {
            $model = Website::findOne($id);
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $w = new Website();
                $w->loadDefaultValues();
                $w->cid = $model->cid;
                $w->title = $model->title;
                $w->url = $model->url;
                $w->sort_order = Website::findMaxSort($w->cid) + 1;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $w->save(false);
                    $model->updateCounters(['collect_num' => 1]);
                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {

                    $transaction->rollBack();
                    //throw $e;

                    return json_encode(['stat' => 'fail']);
                }
            } else {
                return $this->renderAjax('website-collect', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * 网址点击计数
     * @return json
     */
    public function actionWebsiteClick($id) {
        Website::updateAllCounters(['click_num' => 1], ['id' => $id]);
        return true;
    }

    /**
     * 推荐点击计数
     * @return json
     */
    public function actionRecommendClick($id) {
        Recommend::updateAllCounters(['click_num' => 1], ['id' => $id]);
        return true;
    }

}
