<?php

namespace dms\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use dms\models\Parameter;
use dms\models\RepairUnit;
use dms\models\RepairUnitSearch;
use dms\models\RepairWorker;
use dms\models\RepairWorkerType;
use dms\models\RepairWorkerArea;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * RepairController implements the CRUD actions for RepairUnit model.
 */
class RepairController extends Controller {

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
    public function actionType() {
        $dataProvider = new ActiveDataProvider([
            'query' => Parameter::find()->where(['name' => 'repair_type']),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]],
        ]);

        return $this->render('type', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Parameter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTypeCreate() {
        $model = new Parameter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['type-update', 'id' => $model->id]);
        } else {
            return $this->render('type-create', [
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
    public function actionTypeUpdate($id) {
        $model = Parameter::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
        }
        return $this->render('type-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Parameter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTypeDelete($id) {
        $model = Parameter::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all RepairUnit models.
     * @return mixed
     */
    public function actionUnit() {
        $searchModel = new RepairUnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('unit', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RepairUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUnitCreate() {
        $model = new RepairUnit();

        $model->stat = RepairUnit::STAT_OPEN;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功。');
            return $this->redirect(['unit-update', 'id' => $model->id]);
        } else {
            return $this->render('unit-create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RepairUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnitUpdate($id) {
        $model = RepairUnit::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
        }
        return $this->render('unit-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RepairUnit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnitDelete($id) {
        $model = RepairUnit::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all RepairWorker models.
     * @return mixed
     */
    public function actionWorker() {

        $dataProvider = new ActiveDataProvider([
            'query' => RepairWorker::find()->joinWith('unit')->joinWith('user'),
        ]);

        return $this->render('worker', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RepairWorker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionWorkerCreate() {
        $model = new RepairWorker();

        $model->stat = RepairWorker::STAT_OPEN;
        $model->role = RepairWorker::ROLE_WORKER;
        if ($model->load(Yii::$app->request->post())) {
            $model->workday = $model->workday ? implode(',', $model->workday) : NULL;
            if ($model->validate()) {
                $rw = Yii::$app->request->post('RepairWorker');
                $model->type = $rw['type'];
                $model->area = $rw['area'];
                $type = new RepairWorkerType();
                $area = new RepairWorkerArea();
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    if ($model->uid) {
                        $auth = Yii::$app->authManager;
                        $Role_worker = $auth->getRole('repair_worker');
                        $Role_admin = $auth->getRole('repair_admin');
                        $Role_new = $model->role == RepairWorker::ROLE_ADMIN ? $Role_admin : $Role_worker;
                        if (!$auth->getAssignment($Role_new->name, $model->uid)) {
                            $auth->assign($Role_new, $model->uid);
                        }
                    }
                    if ($model->type) {
                        $type->worker = $model->id;
                        foreach ($model->type as $t) {
                            $_type = clone $type;
                            $_type->type = $t;
                            if (!$_type->save()) {
                                throw new \Exception("创建失败");
                            }
                        }
                    }
                    if ($model->area) {
                        $area->worker = $model->id;
                        foreach ($model->area as $a) {
                            $_area = clone $area;
                            $_area->area = $a;
                            if (!$_area->save()) {
                                throw new \Exception("创建失败");
                            }
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '创建成功。');
                    return $this->redirect(['worker-update', 'id' => $model->id]);
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '创建失败。');
                }
            }
        }
        return $this->render('worker-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RepairWorker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionWorkerUpdate($id) {
        $model = RepairWorker::findOne($id);
        $model->type = RepairWorker::get_worker_type($model->id);
        $model->area = RepairWorker::get_worker_area($model->id);
        $model->workday = explode(',', $model->workday);

        $uid = $model->uid;
        $role = $model->role;

        if ($model->load(Yii::$app->request->post())) {
            $model->workday = $model->workday ? implode(',', $model->workday) : NULL;
            if ($model->validate()) {
                $rw = Yii::$app->request->post('RepairWorker');
                $types = $rw['type'] ? $rw['type'] : array();
                $areas = $rw['area'] ? $rw['area'] : array();
                $type = new RepairWorkerType();
                $area = new RepairWorkerArea();

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);

                    if ((int) $model->uid != $uid || (int) $model->role != $role) {

                        $auth = Yii::$app->authManager;
                        $Role_worker = $auth->getRole('repair_worker');
                        $Role_admin = $auth->getRole('repair_admin');
                        $Role_new = $model->role == RepairWorker::ROLE_ADMIN ? $Role_admin : $Role_worker;
                        $Role_old = $role == RepairWorker::ROLE_ADMIN ? $Role_admin : $Role_worker;

                        if (!RepairWorker::find()->where(['uid' => $uid, 'role' => $role])->andWhere(['<>', 'id', $model->id])->one()) {
                            $auth->revoke($Role_old, $uid);
                        }

                        if ($model->uid && !$auth->getAssignment($Role_new->name, $model->uid)) {
                            $auth->assign($Role_new, $model->uid);
                        }
                    }
                    $type->worker = $model->id;
                    $t1 = array_diff($types, $model->type); //新增
                    $t2 = array_diff($model->type, $types); //删除
                    if (count($t1) > 0) {
                        foreach ($t1 as $t) {
                            $_type = clone $type;
                            $_type->type = $t;
                            if (!$_type->save()) {
                                throw new \Exception("修改失败");
                            }
                        }
                    }
                    if (count($t2) > 0) {
                        RepairWorkerType::deleteAll(['worker' => $type->worker, 'type' => $t2]);
                    }
                    $area->worker = $model->id;
                    $a1 = array_diff($areas, $model->area); //新增
                    $a2 = array_diff($model->area, $areas); //删除
                    if (count($a1) > 0) {
                        foreach ($a1 as $a) {
                            $_area = clone $area;
                            $_area->area = $a;
                            if (!$_area->save()) {
                                throw new \Exception("修改失败");
                            }
                        }
                    }
                    if (count($a2) > 0) {
                        RepairWorkerArea::deleteAll(['worker' => $type->worker, 'area' => $a2]);
                    }

                    $transaction->commit();
                    $model->type = $types;
                    $model->area = $areas;
                    Yii::$app->session->setFlash('success', '修改成功。');
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '修改失败。');
                }
            }
            $model->workday = explode(',', $model->workday);
        }

        return $this->render('worker-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RepairWorker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionWorkerDelete($id) {
        $model = RepairWorker::findOne($id);
        if ($model !== null) {
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBind($id) {
        $model = RepairWorker::findOne($id);
        $uid = $model->uid;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);

                if ((int) $model->uid != $uid) {
                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole('repair_worker');
                    if (!RepairWorker::find()->where(['uid' => $uid])->andWhere(['<>', 'id', $model->id])->one()) {
                        $auth->revoke($authorRole, $uid);
                    }
                    if ($model->uid && !$auth->getAssignment($authorRole->name, $model->uid)) {
                        $auth->assign($authorRole, $model->uid);
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('_form-bind', [
                        'model' => $model,
            ]);
        }
    }

}
