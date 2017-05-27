<?php

namespace dms\controllers;

use Yii;
use dms\models\RepairOrder;
use dms\models\RepairOrderSearch;
use dms\models\RepairWorker;
use dms\models\System;
use yii\web\Controller;
use yii\filters\VerbFilter;
use bluehe\phpexcel\Excel;

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
//        $paramsExcel = ''; //这个参数是控制接收view层GridView::widget filter的参数
//        if (($params = Yii::$app->request->queryParams)) {
//
//            foreach ($params as $k => $v) {
//                if ($v) {
//                    $paramsExcel .= $k . '=' . $v . '&';
//                }
//            }
//
//            $paramsExcel = rtrim($paramsExcel, '&');
//        }


        return $this->render('repair-work', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRepairExport() {
        $searchModel = new RepairOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1000);
//        $model = RepairOrder::find()->joinWith('type')->joinWith('area')->joinWith('worker')->all();
//        var_dump(Yii::$app->request->queryParams);
//        exit;
        Excel::export([
            'models' => $dataProvider->getModels(),
            'fileName' => '维修记录(' . date('Y-m-d', time()) . ')',
            'format' => 'Excel5',
            'style' => ['font_name' => '宋体', 'font_size' => 12, 'alignment_horizontal' => 'center', 'alignment_vertical' => 'center', 'row_height' => 20],
            'headerTitle' => ['title' => '维修记录(' . date('Y-m-d', time()) . ')', 'style' => ['font_bold' => true, 'font_size' => 16, 'row_height' => 30]],
            'firstTitle' => ['font_bold' => true, 'row_height' => 20, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
            'columns' => [
                ['attribute' => 'serial', 'style' => ['column_width' => 13, 'row_height' => 20, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]]],
                ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"], 'style' => ['column_width' => 21, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]]],
                ['attribute' => 'name', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'tel', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                [
                    'attribute' => 'repair_type',
                    'value' =>
                    function($model) {
                        return $model->repair_type ? $model->type->v : $model->repair_type;
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'repair_area',
                    'value' =>
                    function($model) {
                        return $model->repair_area ? $model->area->name : $model->repair_area;
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                ['attribute' => 'address', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'content', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                [
                    'attribute' => 'worker_id',
                    'value' =>
                    function($model) {
                        return $model->worker_id ? $model->worker->name : $model->worker_id;
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'eval',
                    'value' =>
                    function($model) {
                        return $model->evaluate1 ? Yii::$app->formatter->asDecimal(($model->evaluate1 + $model->evaluate2 + $model->evaluate3) / 3, 2) : NULL;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'stat',
                    'value' =>
                    function($model) {
                        return $model->Stat;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
            ],
            'headers' => [
                'id' => 'ID',
                'uid' => '报修人',
                'serial' => '编号',
                'repair_type' => '类型',
                'repair_area' => '区域',
                'address' => '详细地址',
                'title' => '标题',
                'content' => '内容',
                'eval' => '综合评价',
                'created_at' => '报修时间',
                'accept_at' => '受理时间',
                'accept_uid' => '受理人',
                'dispatch_at' => '派工时间',
                'dispatch_uid' => '派工人',
                'repair_at' => '维修时间',
                'repair_uid' => '维修人员',
                'worker_id' => '维修工',
                'end_at' => '结束时间',
                'note' => '备注',
                'stat' => '状态',
            ],
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a single RepairOrder model.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairView($id) {
        $query = RepairOrder::find()->where(['id' => $id]);

        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            //维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
            //受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
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
    public function actionRepairUpdate($id) {
        $query = RepairOrder::find()->where(['id' => $id, 'stat' => RepairOrder::STAT_OPEN]);
        if (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $model = $query->one();

        if ($model->load(Yii::$app->request->post())) {

//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//                return \yii\widgets\ActiveForm::validate($model);
//            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('repair-update', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /**
     * Updates an existing RepairOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairAccept($id) {
        $query = RepairOrder::find()->where(['id' => $id, 'stat' => RepairOrder::STAT_OPEN]);
        if (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $model = $query->one();

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
                $query = RepairOrder::find()->where(['id' => $id, 'stat' => RepairOrder::STAT_OPEN]);
                if (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
                    $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
                    $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
                    $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
                }
                $model = $query->one();
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
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
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
                if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
                    $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
                    $query->andWhere(['worker_id' => $worker]);
                } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
                    $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
                    $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
                    $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
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
