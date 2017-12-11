<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use dh\models\Suggest;
use dh\models\Website;
use dh\models\Category;

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
        $suggest = new ActiveDataProvider([
            'query' => Suggest::find()->where(['uid' => Yii::$app->user->identity->id]),
            'pagination' => ['pageSize' => 5],
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);
        return $this->render('index', [
                    'suggest' => $suggest,
        ]);
    }

    /**
     * Lists all Suggest models.
     * @return mixed
     */
    public function actionSuggest() {
        $dataProvider = new ActiveDataProvider([
            'query' => Suggest::find()->where(['uid' => Yii::$app->user->identity->id]),
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);

        return $this->render('suggest', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Suggest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSuggestCreate() {
        $model = new Suggest();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->uid = Yii::$app->user->identity->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '发布成功。');
            } else {
                Yii::$app->session->setFlash('error', '发布失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('suggest-update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionSuggestUpdate($id) {

        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Suggest::STAT_OPEN]);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('suggest-update', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    public function actionSuggestClose($id) {
        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Suggest::STAT_OPEN]);

        if ($model !== null) {
            $model->stat = Suggest::STAT_CLOSE;
            $model->updated_at = time();
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSuggestView($id) {
        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id]);
        if ($model !== null) {
            return $this->renderAjax('suggest-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Lists all Website models.
     * @return mixed
     */
    public function actionWebsite() {
        $dataProvider = new ActiveDataProvider([
            'query' => Website::find()->joinWith(['c', 'user'])->where(['uid' => Yii::$app->user->identity->id])->andWhere(['not', [Website::tableName() . '.stat' => Website::STAT_DELETE]])->orderBy([Category::tableName() . '.sort_order' => SORT_ASC, Website::tableName() . '.stat' => SORT_ASC, Website::tableName() . '.sort_order' => SORT_ASC]),
        ]);
        $dataProvider->setSort(false);

        return $this->render('website', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionWebsiteDelete($id) {
        $model = Website::findOne($id);
        if ($model !== null && $model->stat !== Website::STAT_OPEN) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionWebsiteRecovery($id) {
        $model = Website::findOne($id);
        $model->stat = Website::STAT_OPEN;
        $model->is_open = Website::ISOPEN_OPEN;
        $model->sort_order = Website::findMaxSort($model->cid, Website::STAT_OPEN) + 1;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->c->stat != Category::STAT_OPEN) {
                $cate = Category::findOne($model->cid);
                $cate->stat = Category::STAT_OPEN;
                $cate->sort_order = Category::findMaxSort($cate->uid, Category::STAT_OPEN) + 1;
                $cate->save();
            }
            $model->save();
            $transaction->commit();
            Yii::$app->session->setFlash('success', '操作成功。');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', '操作失败。');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

}
