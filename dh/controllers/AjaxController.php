<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use dh\models\User;
use dh\models\Category;
use dh\models\Website;
use dh\models\Recommend;
use dh\models\WebsiteClick;
use dh\models\WebsiteShare;

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
     * 网址点击计数
     * @return json
     */
    public function actionWebsiteClick($id) {
        $model = Website::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $c = new WebsiteClick();
            $c->uid = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->id;
            $c->website = $id;
            $c->ip = Yii::$app->request->userIP;
            $c->created_at = time();
            $c->save(false);
            $model->updateCounters(['click_num' => 1]);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {

            $transaction->rollBack();
            //throw $e;
            return false;
        }
    }

    /**
     * 推荐点击计数
     * @return json
     */
    public function actionRecommendClick($id) {
        Recommend::updateAllCounters(['click_num' => 1], ['id' => $id]);
        return true;
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
                $cate->loadDefaultValues();
                $cate->title = $model->title;
                $cate->uid = Yii::$app->user->identity->id;
                $cate->sort_order = Category::findMaxSort(Yii::$app->user->identity->id, Category::STAT_OPEN) + 1;
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
                        $w->sort_order = Website::findMaxSort($cate->id, Website::STAT_OPEN) + 1;
                        $w->share_status = Website::SHARE_COLLECT;
                        $w->share_id = $website->id;
                        $w->save(false);
                        $website->updateCounters(['collect_num' => 1]);
                    }
                    $model->updateCounters(['collect_num' => 1]);

                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {

                    $transaction->rollBack();
                    //throw $e;

                    return json_encode(['stat' => 'fail', 'msg' => '操作失败！']);
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
                $w->sort_order = Website::findMaxSort($w->cid, Website::STAT_OPEN) + 1;
                $w->share_status = Website::SHARE_COLLECT;
                $w->share_id = $model->id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $w->save(false);
                    $model->updateCounters(['collect_num' => 1]);
                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {

                    $transaction->rollBack();
                    //throw $e;

                    return json_encode(['stat' => 'fail', 'msg' => '操作失败！']);
                }
            } else {
                return $this->renderAjax('website-collect', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * 编辑分类
     * @return bool
     */
    public function actionCategoryEdit($id) {

        $model = Category::findOne($id);
        if (!Yii::$app->user->isGuest && $model->uid == Yii::$app->user->identity->id) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return json_encode(['stat' => 'success', 'title' => $model->title]);
            } else {
                return $this->renderAjax('category-edit', [
                            'model' => $model,
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * 删除分类
     * @return boolean
     */
    public function actionCategoryDelete($id) {

        $model = Category::findOne($id);
        if (!Yii::$app->user->isGuest && $model->uid == Yii::$app->user->identity->id) {

            if (Category::get_category_num($model->uid, '', Category::STAT_OPEN) > 1) {
                $model->stat = Category::STAT_CLOSE;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    Website::updateAll(['stat' => Website::STAT_CLOSE], ['cid' => $id, 'stat' => Website::STAT_OPEN]);
                    Category::updateAllCounters(['sort_order' => -1], ['and', ['and', 'uid' => $model->uid, 'stat' => Category::STAT_OPEN], ['>', 'sort_order', $model->sort_order]]);
                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return json_encode(['stat' => 'fail', 'msg' => '操作失败']);
                }
            } else {
                return json_encode(['stat' => 'fail', 'msg' => '至少保留一个分类']);
            }
        }
        return json_encode(['stat' => 'error']);
    }

    /**
     * 添加分类
     * @return json
     */
    public function actionCategoryAdd($id) {

        $cate = Category::findOne($id);
        if (!Yii::$app->user->isGuest && $cate->uid == Yii::$app->user->identity->id) {
            $model = new Category();
            $model->loadDefaultValues();
            $model->uid = Yii::$app->user->identity->id;
            $model->sort_order = $cate->sort_order + 1;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    Category::updateAllCounters(['sort_order' => 1], ['and', ['and', 'uid' => $model->uid, 'stat' => Category::STAT_OPEN], ['>=', 'sort_order', $model->sort_order]]);
                    $model->save(false);
                    $transaction->commit();
                    return json_encode(['stat' => 'success', 'id' => $model->id, 'title' => $model->title]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return json_encode(['stat' => 'fail', 'msg' => '操作失败']);
                }
            } else {
                return $this->renderAjax('category-add', [
                            'model' => $model, 'id' => $id,
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * 添加网址
     * @return json
     */
    public function actionWebsiteAdd($id) {

        $cate = Category::findOne($id);
        if (!Yii::$app->user->isGuest && $cate->uid == Yii::$app->user->identity->id) {
            $model = new Website();
            $model->loadDefaultValues();
            $model->cid = $id;
            $model->sort_order = Website::findMaxSort($model->cid, Website::STAT_OPEN) + 1;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return json_encode(['stat' => 'success', 'id' => $model->id, 'title' => $model->title, 'url' => $model->url, 'host' => $model->host]);
            } else {
                return $this->renderAjax('website-add', [
                            'model' => $model,
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * 网址分享
     * @return json
     */
    public function actionWebsiteShare($id) {

        $website = Website::findOne($id);
        if (!Yii::$app->user->isGuest && $website->c->uid == Yii::$app->user->identity->id) {
            $model = new WebsiteShare();
            $model->loadDefaultValues();
            $model->uid = Yii::$app->user->identity->id;
            $model->wid = $website->id;
            $model->title = $website->title;
            $model->url = $website->url;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    $website->share_status = Website::SHARE_WAIT;
                    $website->save(false);
                    $transaction->commit();
                    return json_encode(['stat' => 'success']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return json_encode(['stat' => 'fail', 'msg' => '操作失败']);
                }
            } else {
                return $this->renderAjax('website-share', [
                            'model' => $model,
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * 编辑网址
     * @return json
     */
    public function actionWebsiteEdit($id) {

        $model = Website::findOne($id);
        if (!Yii::$app->user->isGuest && $model->c->uid == Yii::$app->user->identity->id) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return json_encode(['stat' => 'success', 'title' => $model->title, 'url' => $model->url, 'host' => $model->host]);
            } else {
                return $this->renderAjax('website-edit', [
                            'model' => $model,
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * 网址删除
     * @return bool
     */
    public function actionWebsiteDelete($id) {
        $model = Website::findOne($id);
        if (!Yii::$app->user->isGuest && $model->c->uid == Yii::$app->user->identity->id) {
            $model->stat = Website::STAT_CLOSE;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                Website::updateAllCounters(['sort_order' => -1], ['and', ['and', 'cid' => $model->cid, 'stat' => Website::STAT_OPEN], ['>', 'sort_order', $model->sort_order]]);
                $transaction->commit();
                return json_encode(['stat' => 'success']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return json_encode(['stat' => 'fail', 'msg' => '操作失败']);
            }
        }
        return json_encode(['stat' => 'error']);
    }

    /**
     * 公开/私有网址
     * @return string
     */
    public function actionWebsiteOpen($id) {

        $model = Website::findOne($id);
        if (!Yii::$app->user->isGuest && $model->c->uid == Yii::$app->user->identity->id) {
            $model->is_open = $model->is_open == Website::ISOPEN_OPEN ? Website::ISOPEN_CLOSE : Website::ISOPEN_OPEN;
            if ($model->save()) {
                return $model->is_open == Website::ISOPEN_OPEN ? 'open' : 'close';
            }
        }
        return false;
    }

}
