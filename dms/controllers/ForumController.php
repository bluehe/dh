<?php

namespace dms\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use dms\models\Forum;
use dms\models\Parameter;
use dms\models\Broom;
use dms\models\BroomSearch;

/**
 * ForumController implements the CRUD actions for forum model.
 */
class ForumController extends Controller {

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
     * Lists all Paremeter models.
     * @return mixed
     */
    public function actionFloor() {
        $dataProvider = new ActiveDataProvider([
            'query' => Parameter::find()->where(['name' => 'floor']),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]],
        ]);

        return $this->render('floor', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Parameter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFloorCreate() {
        $model = new Parameter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['floor-update', 'id' => $model->id]);
        } else {
            return $this->render('floor-create', [
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
    public function actionFloorUpdate($id) {
        $model = Parameter::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
            return $this->redirect(['floor-update', 'id' => $model->id]);
        }
        return $this->render('floor-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Parameter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFloorDelete($id) {
        $model = Parameter::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(['floor']);
    }

    /**
     * Lists all forum models.
     * @return mixed
     */
    public function actionForum() {

        $dataProvider = new ActiveDataProvider([
            'query' => Forum::find()->joinWith('fups'),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]],
        ]);
        $sort = $dataProvider->getSort();
        $sort->attributes['sort_order'] = [
            'asc' => ['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC],
            'desc' => ['fsort' => SORT_DESC, 'mark' => SORT_DESC, 'fup' => SORT_ASC, 'sort_order' => SORT_DESC, 'id' => SORT_DESC],
        ];

        $dataProvider->setSort($sort);

        return $this->render('forum', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new forum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionForumCreate() {
        $model = new Forum();
        $model->mold = 1;
        $model->stat = 1;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                $model->mark = $model->fup ? $model->fup : $model->id;
                $model->fsort = $model->fup ? $model->fups->sort_order : $model->sort_order;
                $model->save();

                $transaction->commit();
                Yii::$app->session->setFlash('success', '创建成功。');
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '创建失败。');
            }
//            if ($model->save()) {
//                $model->mark = $model->fup ? $model->fup : $model->id;
//                $model->fsort = $model->fup ? $model->fups->sort_order : $model->sort_order;
//                $model->save();
//                Yii::$app->session->setFlash('success', '创建成功。');
//            } else {
//                Yii::$app->session->setFlash('error', '创建失败。');
//            }
            return $this->redirect(['forum-update', 'id' => $model->id]);
        } else {
            return $this->render('forum-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing forum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionForumUpdate($id) {
        $model = Forum::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->mark = $model->fup ? $model->fup : $model->id;
            $model->fsort = $model->fup ? $model->fups->sort_order : $model->sort_order;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                if (!$model->fup) {
                    Forum::updateAll(['fsort' => $model->sort_order], ['fup' => $model->id]);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', '修改成功。');
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '修改失败。');
            }
//            if ($model->save()) {
//                if (!$model->fup) {
//                    Forum::updateAll(['fsort' => $model->sort_order], ['fup' => $model->id]);
//                }
//                Yii::$app->session->setFlash('success', '修改成功。');
//            } else {
//                Yii::$app->session->setFlash('error', '修改失败。');
//            }
            return $this->redirect(['forum-update', 'id' => $model->id]);
        }
        return $this->render('forum-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing forum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionForumDelete($id) {
        $model = Forum::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(['forum']);
    }

    /**
     * Lists all Broom models.
     * @return mixed
     */
    public function actionBroom() {
        $searchModel = new BroomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('broom', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Broom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBroomCreate() {
        $model = new Broom();
        $model->stat = 1;

        if ($model->load(Yii::$app->request->post())) {

            $br1 = explode("~", $model->name);
            $br2 = explode(',', $model->name);
            $brooms = array();
            if (count($br1) > 1) {
                //用~分隔
                for ($a = $br1[0]; $a <= $br1[1]; $a++) {
                    $brooms[] = $a;
                }
            } else {
                $brooms = $br2;
            }
            //添加数据
            $res = 1;
            if (count($brooms) > 0) {

                foreach ($brooms as $broom) {

                    $_model = clone $model;
                    $_model->name = (string) $broom;

                    $res = $res && $_model->save();
                }
            } else {
                $res = 0;
            }


            if ($res) {

                Yii::$app->session->setFlash('success', '创建成功。');
            } else {
                Yii::$app->session->setFlash('error', '创建失败。');
            }

            return $this->render('broom-create', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('broom-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Broom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBroomUpdate($id) {
        $model = Broom::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
            return $this->redirect(['broom-update', 'id' => $model->id]);
        }
        return $this->render('broom-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Broom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBroomDelete($id) {
        $model = Broom::findOne($id);
        if ($model !== null) {
            $model->delete();
        }
        return $this->redirect(['broom']);
    }

}
