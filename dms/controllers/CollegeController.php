<?php

namespace dms\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use dms\models\College;
use dms\models\Major;
use dms\models\MajorSearch;
use dms\models\Parameter;

/**
 * CollegeController implements the CRUD actions for College model.
 */
class CollegeController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all College models.
     * @return mixed
     */
    public function actionCollege() {
        $dataProvider = new ActiveDataProvider([
            'query' => College::find(),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]],
        ]);

        return $this->render('college', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new College model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCollegeCreate() {
        $model = new College();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['college-update', 'id' => $model->id]);
        } else {
            return $this->render('college-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing College model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCollegeUpdate($id) {
        $model = College::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
//            return $this->redirect(['college-update', 'id' => $model->id]);
        }
        return $this->render('college-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing College model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCollegeDelete($id) {
        $model = College::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all Major models.
     * @return mixed
     */
    public function actionMajor() {

        $searchModel = new MajorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('major', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Major model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMajorCreate() {
        $model = new Major();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['major-update', 'id' => $model->id]);
        } else {
            return $this->render('major-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Major model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMajorUpdate($id) {
        $model = Major::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
//            return $this->redirect(['major-update', 'id' => $model->id]);
        }
        return $this->render('major-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Major model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMajorDelete($id) {
        $model = Major::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all Paremeter models.
     * @return mixed
     */
    public function actionGrade() {
        $dataProvider = new ActiveDataProvider([
            'query' => Parameter::find()->where(['name' => 'grade']),
        ]);

        return $this->render('grade', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Parameter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGradeCreate() {
        $model = new Parameter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['grade-update', 'id' => $model->id]);
        } else {
            return $this->render('grade-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Parameter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGradeUpdate($id) {
        $model = Parameter::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
//            return $this->redirect(['grade-update', 'id' => $model->id]);
        }
        return $this->render('grade-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Parameter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGradeDelete($id) {
        $model = Parameter::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

}
