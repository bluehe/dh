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
    public function actionUsers() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('users', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUsersChange($id) {
        $auth = Yii::$app->authManager;
        $Role_admin = $auth->getRole('admin');
        if (!$auth->getAssignment($Role_admin->name, $id)) {
            $model = User::findOne($id);
            $model->status = User::STATUS_ACTIVE == $model->status ? User::STATUS_DELETED : User::STATUS_ACTIVE;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
        } else {
            Yii::$app->session->setFlash('error', '不能改变管理员状态。');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUsersUpdate($id) {
        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\bootstrap\ActiveForm::validate($model);
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
        }
        return $this->render('users-update', [
                    'model' => $model,
        ]);
    }

}
