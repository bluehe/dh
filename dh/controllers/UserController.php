<?php

namespace dh\controllers;

use Yii;
use dh\models\User;
use dh\models\UserSearch;
use dh\models\UserLevel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * UserController implements the CRUD actions for UserLevel model.
 */
class UserController extends Controller {

    /**
     * Lists all UserLevel models.
     * @return mixed
     */
    public function actionLevel() {
        $dataProvider = new ActiveDataProvider([
            'query' => UserLevel::find()->orderBy(['level' => SORT_ASC]),
        ]);

        return $this->render('level', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new UserLevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLevelCreate() {
        $model = new UserLevel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['level-update', 'id' => $model->id]);
        } else {
            return $this->render('level-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserLevel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLevelUpdate($id) {
        $model = UserLevel::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
        }
        return $this->render('level-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserLevel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLevelDelete($id) {
        $model = UserLevel::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionUser() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('user', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
