<?php

namespace dms\controllers;

use Yii;
use dms\models\RepairOrder;
use dms\models\RepairOrderSearch;
use dms\models\RepairWorker;
use dms\models\System;
use yii\web\Controller;
use yii\filters\VerbFilter;

class WorkController extends Controller {

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
     * Lists all RepairOrder models.
     * @return mixed
     */
    public function actionRepairWork() {

        $searchModel = new RepairOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('repair-work', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RepairOrder model.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairView($id) {
        $query = RepairOrder::find()->where(['id' => $id]);
        if (!Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        }
        $model = $query->one();

        if ($model !== null) {
            return $this->renderAjax('/business/repair-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing RepairOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairAccept($id) {
        $model = RepairOrder::findOne(['id' => $id, 'stat' => RepairOrder::STAT_OPEN]);

        if ($model->load(Yii::$app->request->post())) {

            $model->accept_at = time();
            $model->accept_uid = Yii::$app->user->identity->id;
            if ($model->stat == RepairOrder::STAT_DISPATCH) {
                $model->setScenario('dispatch');
                $model->dispatch_at = time();
                $model->dispatch_uid = Yii::$app->user->identity->id;
                $model->note = NULL;
            } elseif ($model->stat == RepairOrder::STAT_ACCEPT) {
                $model->worker_id = NULL;
                $model->note = NULL;
            } else {
                $model->worker_id = NULL;
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                if (System::getValue('business_accept') === '1') {
                    $model->stat = RepairOrder::STAT_DISPATCH;
                    if (System::getValue('business_dispatch') === '2') {
                        $worker = $model->getWorkerList($model->repair_type, $model->repair_area);
                        $model->worker_id = array_rand($worker);
                    }
                } else {
                    $model->stat = RepairOrder::STAT_ACCEPT;
                }

                return $this->renderAjax('repair-accept', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    public function actionRepairAccepts($ids) {
        $order_ids = explode(',', $ids);
        $business_accept = System::getValue('business_accept');
        $business_dispatch = System::getValue('business_dispatch');
        $transaction = Yii::$app->db->beginTransaction(); //事务无效
        try {
            foreach ($order_ids as $id) {
                $model = RepairOrder::findOne(['id' => $id, 'stat' => RepairOrder::STAT_OPEN]);
                if ($model !== null) {
                    if ($business_accept === '1') {

                        if ($business_dispatch === '2') {
                            $worker = $model->getWorkerList($model->repair_type, $model->repair_area);
                        } else {
                            $worker = $model->getWorkerList();
                        }
                        $model->worker_id = array_rand($worker);

                        if ($model->worker_id) {
                            $model->stat = RepairOrder::STAT_DISPATCH;
                        } else {
                            $model->stat = RepairOrder::STAT_ACCEPT;
                        }
                    } else {
                        $model->stat = RepairOrder::STAT_ACCEPT;
                    }
                    $model->accept_at = time();
                    $model->accept_uid = Yii::$app->user->identity->id;
                    if ($model->stat == RepairOrder::STAT_DISPATCH) {
                        $model->dispatch_at = time();
                        $model->dispatch_uid = Yii::$app->user->identity->id;
                    }
                    if (!$model->save()) {
                        throw new \Exception("操作失败");
                    }
                }
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', '操作成功。');
        } catch (\Exception $e) {

            $transaction->rollBack();
//                throw $e;
            Yii::$app->session->setFlash('error', '操作失败。');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRepairDispatch($id) {
        $model = RepairOrder::findOne(['id' => $id, 'stat' => [RepairOrder::STAT_ACCEPT, RepairOrder::STAT_DISPATCH]]);
        $model->setScenario('dispatch');
        if ($model->load(Yii::$app->request->post())) {

            $model->stat = RepairOrder::STAT_DISPATCH;
            $model->dispatch_at = time();
            $model->dispatch_uid = Yii::$app->user->identity->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                if ($model->stat === RepairOrder::STAT_ACCEPT && System::getValue('business_dispatch') === '2') {
                    $worker = $model->getWorkerList($model->repair_type, $model->repair_area);
                    $model->worker_id = array_rand($worker);
                }

                return $this->renderAjax('repair-dispatch', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    public function actionRepairRepair($id) {
        $query = RepairOrder::find()->where(['id' => $id, 'stat' => RepairOrder::STAT_DISPATCH]);
        if (!Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        }
        $model = $query->one();

        if ($model !== null) {
            $model->stat = RepairOrder::STAT_REPAIRED;
            $model->repair_at = time();
            $model->repair_uid = Yii::$app->user->identity->id;
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRepairRepairs($ids) {
        $order_ids = explode(',', $ids);

        $transaction = Yii::$app->db->beginTransaction(); //事务无效
        try {
            foreach ($order_ids as $id) {
                $query = RepairOrder::find()->where(['id' => $id, 'stat' => RepairOrder::STAT_DISPATCH]);
                if (!Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
                    $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
                    $query->andWhere(['worker_id' => $worker]);
                }
                $model = $query->one();

                if ($model !== null) {
                    $model->stat = RepairOrder::STAT_REPAIRED;
                    $model->repair_at = time();
                    $model->repair_uid = Yii::$app->user->identity->id;
                    if (!$model->save()) {
                        throw new \Exception("操作失败");
                    }
                }
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', '操作成功。');
        } catch (\Exception $e) {

            $transaction->rollBack();
//                throw $e;
            Yii::$app->session->setFlash('error', '操作失败。');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
