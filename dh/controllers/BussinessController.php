<?php

namespace dh\controllers;

use Yii;
use dh\models\Suggest;
use yii\web\Controller;
use dh\models\SuggestSearch;

class BussinessController extends Controller {

    /**
     * Lists all Suggest models.
     * @return mixed
     */
    public function actionSuggestList() {
        $searchModel = new SuggestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('suggest-list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Suggest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSuggestView($id) {
        $model = Suggest::findOne(['id' => $id]);
        if ($model !== null) {
            return $this->renderAjax('/person/suggest-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionSuggestReply($id) {

        $model = Suggest::find()->where(['id' => $id, 'stat' => Suggest::STAT_OPEN])->one();
        $model->setScenario('reply');

        if ($model->load(Yii::$app->request->post())) {
            $model->reply_uid = Yii::$app->user->identity->id;
            $model->stat = Suggest::STAT_REPLY;
            $model->updated_at = time();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('suggest-reply', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

}
