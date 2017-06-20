<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use bluehe\phpexcel\Excel;
use dms\models\RepairOrder;
use dms\models\RepairOrderSearch;
use dms\models\System;
use dms\models\Forum;
use dms\models\Room;
use dms\models\Bed;
use dms\models\Teacher;
use dms\models\TeacherSearch;
use dms\models\CheckOrder;
use dms\models\Pickup;
use dms\models\PickupSearch;

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
                ['attribute' => 'tel', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]], 'column_width' => 13],],
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
                        return $model->repair_area ? Forum::get_forum_allname($model->repair_area) : $model->repair_area;
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
        $query->andWhere(RepairOrder::get_permission());
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
        $query->andWhere(RepairOrder::get_permission());

        $model = $query->one();

        if ($model->load(Yii::$app->request->post())) {

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
        $query->andWhere(RepairOrder::get_permission());
//        if (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
//            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
//            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
//        }
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
                $query->andWhere(RepairOrder::get_permission());
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
        $query->andWhere(RepairOrder::get_permission());

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
                $query->andWhere(RepairOrder::get_permission());

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

    /**
     * Lists all Pickup models.
     * @return mixed
     */
    public function actionPickupWork() {

        $searchModel = new PickupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pickup-work', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPickupClose($id) {
        $model = Pickup::findOne(['id' => $id, 'stat' => Pickup::STAT_OPEN]);

        if ($model !== null) {
            $model->end_uid = Yii::$app->user->identity->id;
            $model->stat = Pickup::STAT_CLOSE;
            $model->end_at = time();
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPickupView($id) {
        $model = Pickup::findOne(['id' => $id]);
        if ($model !== null) {
            return $this->renderAjax('/business/pickup-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionPickupExport() {
        $searchModel = new PickupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1000);

        Excel::export([
            'models' => $dataProvider->getModels(),
            'fileName' => '拾物招领记录(' . date('Y-m-d', time()) . ')',
            'format' => 'Excel5',
            'style' => ['font_name' => '宋体', 'font_size' => 12, 'alignment_horizontal' => 'center', 'alignment_vertical' => 'center', 'row_height' => 20],
            'headerTitle' => ['title' => '拾物招领记录(' . date('Y-m-d', time()) . ')', 'style' => ['font_bold' => true, 'font_size' => 16, 'row_height' => 30]],
            'firstTitle' => ['font_bold' => true, 'row_height' => 20, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
            'columns' => [
                ['attribute' => 'created_at', 'format' => ["date", "php:Y-m-d H:i:s"], 'style' => ['column_width' => 21, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]]],
                [
                    'attribute' => 'type',
                    'value' =>
                    function($model) {
                        return $model->Type;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                ['attribute' => 'goods', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'address', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'content', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'name', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'tel', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]], 'column_width' => 13],],
                [
                    'attribute' => 'end_at',
                    'value' =>
                    function($model) {
                        return $model->end_at ? date('Y-m-d H:i:s', $model->end_at) : NULL;   //主要通过此种方式实现
                    },
                    'style' => ['column_width' => 21, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]]],
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
                'type' => '类型',
                'uid' => '用户',
                'name' => '联系人',
                'tel' => '联系电话',
                'goods' => '物品',
                'address' => '地址',
                'content' => '内容',
                'created_at' => '发布时间',
                'end_at' => '结束时间',
                'end_uid' => '结束人',
                'stat' => '状态',
            ],
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBuilding() {
        $cache = Yii::$app->cache;
        $total = $cache->get('building_total');
        $data = $cache->get('building_data');
        if ($total === false || $data === false) {
            //统计
            $total = [];
            $total['broom_num'] = Room::find()->where(['rid' => NULL])->select(['count(*)'])->groupBy(['fid'])->indexBy('fid')->column();
            $total['broom_open'] = Room::find()->where(['rid' => NULL, 'stat' => Room::STAT_OPEN])->select(['count(*)'])->groupBy(['fid'])->indexBy('fid')->column();
            $total['sroom_open'] = Room::find()->where(['stat' => Room::STAT_OPEN])->andWhere(['not', ['rid' => NULL]])->select(['count(*)'])->groupBy(['fid'])->indexBy('fid')->column();
            $total['bed_open'] = Bed::find()->joinWith('room')->where([Room::tableName() . '.stat' => Room::STAT_OPEN, Bed::tableName() . '.stat' => Bed::STAT_OPEN])->select(['count(*)'])->groupBy(['fid'])->indexBy('fid')->column();
            $total['bed_num'] = Bed::find()->where(['stat' => Bed::STAT_OPEN])->select(['count(*)'])->groupBy(['rid'])->indexBy('rid')->column();
            $total['forum_check'] = CheckOrder::find()->joinWith('room')->where([CheckOrder::tableName() . '.stat' => [CheckOrder::STAT_CHECKIN, CheckOrder::STAT_CHECKWAIT]])->select(['count(*)'])->groupBy(['fid'])->indexBy('fid')->column();
            $total['room_check'] = CheckOrder::find()->joinWith('bed0')->where([CheckOrder::tableName() . '.stat' => [CheckOrder::STAT_CHECKIN, CheckOrder::STAT_CHECKWAIT]])->select(['count(*)'])->groupBy(['rid'])->indexBy('rid')->column();
            $total['bed_check'] = CheckOrder::find()->where(['stat' => [CheckOrder::STAT_CHECKIN, CheckOrder::STAT_CHECKWAIT]])->select(['stat'])->indexBy('bed')->column();
            $total['bed_student'] = CheckOrder::find()->joinWith('student')->where(['related_table' => 'student', CheckOrder::tableName() . '.stat' => [CheckOrder::STAT_CHECKIN, CheckOrder::STAT_CHECKWAIT]])->select(['bed', 'name', 'gender', 'grade', 'related_id'])->indexBy('bed')->asArray()->all();


            $data = [];
            $forums = Forum::get_forumfup_id(null, Forum::STAT_OPEN);

            foreach ($forums as $k => $p) {
                $data[$k]['forum_name'] = $p;
                //本级楼苑
                //楼层
                $floors = Room::get_room_floor($k);
                $floor = [];
                foreach ($floors as $k_f => $name) {
                    $floor[$k_f]['floor_name'] = $name;

                    //大室
                    $brooms = Room::get_broom($k, $k_f);
                    foreach ($brooms as $bid => $one) {
                        $brooms[$bid]['sroom'] = Room::get_sroom($bid);


                        if ($brooms[$bid]['sroom']) {
                            //小室床位
                            foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                $brooms[$bid]['sroom'][$sid]['bed'] = Bed::get_room_bed($sid);
                            }
                        } else {
                            //大室床位
                            $brooms[$bid]['bed'] = Bed::get_room_bed($bid);
                        }

                        //设定大室床位数
                        if (!isset($total['bed_num'][$bid])) {
                            $num = 0;
                            foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                if (isset($total['bed_num'][$sid])) {
                                    $num += $total['bed_num'][$sid];
                                }
                            }
                            if ($num) {
                                $total['bed_num'][$bid] = $num;
                            }
                        }

                        //设定大室入住数
                        if (!isset($total['room_check'][$bid])) {
                            $num = 0;
                            foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                if (isset($total['room_check'][$sid])) {
                                    $num += $total['room_check'][$sid];
                                }
                            }
                            if ($num) {
                                $total['room_check'][$bid] = $num;
                            }
                        }
                    }


                    $floor[$k_f]['broom'] = $brooms;
                }
                $data[$k]['floor'] = $floor;

                //下级楼苑
                $subforums = Forum::get_forumsub_id($k, Forum::STAT_OPEN);

                $sub = [];
                $forum_check = 0;
                $broom_num = 0;
                $broom_open = 0;
                $sroom_open = 0;
                $bed_open = 0;
                foreach ($subforums as $sub_id => $c) {
                    $sub[$sub_id]['forum_name'] = $c;

                    //一级楼苑大室数量
                    if (isset($total['forum_check'][$sub_id])) {
                        $forum_check += $total['forum_check'][$sub_id];
                    }
                    if (isset($total['broom_num'][$sub_id])) {
                        $broom_num += $total['broom_num'][$sub_id];
                    }
                    if (isset($total['broom_open'][$sub_id])) {
                        $broom_open += $total['broom_open'][$sub_id];
                    }
                    if (isset($total['sroom_open'][$sub_id])) {
                        $sroom_open += $total['sroom_open'][$sub_id];
                    }
                    if (isset($total['bed_open'][$sub_id])) {
                        $bed_open += $total['bed_open'][$sub_id];
                    }

                    //楼层
                    $floors = Room::get_room_floor($sub_id);
                    $floor = [];
                    foreach ($floors as $k_f => $name) {
                        $floor[$k_f]['floor_name'] = $name;

                        //大室
                        $brooms = Room::get_broom($sub_id, $k_f);
                        foreach ($brooms as $bid => $one) {
                            $brooms[$bid]['sroom'] = Room::get_sroom($bid);
                            if ($brooms[$bid]['sroom']) {
                                //小室床位
                                foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                    $brooms[$bid]['sroom'][$sid]['bed'] = Bed::get_room_bed($sid);
                                }
                            } else {
                                //大室床位
                                $brooms[$bid]['bed'] = Bed::get_room_bed($bid);
                            }
                            if (!isset($total['bed_num'][$bid])) {
                                $bed_num = 0;
                                foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                    if (isset($total['bed_num'][$sid])) {
                                        $bed_num += $total['bed_num'][$sid];
                                    }
                                }
                                if ($bed_num) {
                                    $total['bed_num'][$bid] = $bed_num;
                                }
                            }
                            //设定大室入住数
                            if (!isset($total['room_check'][$bid])) {
                                $num = 0;
                                foreach ($brooms[$bid]['sroom'] as $sid => $sroom) {
                                    if (isset($total['room_check'][$sid])) {
                                        $num += $total['room_check'][$sid];
                                    }
                                }
                                if ($num) {
                                    $total['room_check'][$bid] = $num;
                                }
                            }
                        }


                        $floor[$k_f]['broom'] = $brooms;
                    }
                    $sub[$sub_id]['floor'] = $floor;
                }
                $data[$k]['children'] = $sub;

                if (!isset($total['forum_check'][$k]) && $forum_check) {
                    $total['forum_check'][$k] = $forum_check;
                }

                if (!isset($total['broom_num'][$k]) && $broom_num) {
                    $total['broom_num'][$k] = $broom_num;
                    if ($broom_open) {
                        $total['broom_open'][$k] = $broom_open;
                    }
                    if ($sroom_open) {
                        $total['sroom_open'][$k] = $sroom_open;
                    }
                    if ($bed_open) {
                        $total['bed_open'][$k] = $bed_open;
                    }
                }
            }
            $cache->set('building_total', $total);
            $cache->set('building_data', $data);
        }
        return $this->render('building', [
                    'forums' => $data, 'total' => $total
        ]);
    }

    /**
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionTeacher() {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('teacher', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTeacherCreate() {
        $model = new Teacher();
        $model->stat = Teacher::STAT_OPEN;
        $model->gender = Teacher::GENDER_MALE;
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    if ($model->uid) {
                        $auth = Yii::$app->authManager;
                        $Role_new = $auth->getRole('teacher');
                        if (!$auth->getAssignment($Role_new->name, $model->uid)) {
                            $auth->assign($Role_new, $model->uid);
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '添加成功。');
                    return $this->redirect(['teacher-update', 'id' => $model->id]);
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '添加失败。');
                }
            }
        }
        return $this->render('teacher-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTeacherUpdate($id) {
        $model = Teacher::findOne($id);

        $uid = $model->uid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);

                    if ((int) $model->uid != $uid) {

                        $auth = Yii::$app->authManager;
                        $Role = $auth->getRole('teacher');

                        if (!Teacher::find()->where(['uid' => $uid])->andWhere(['<>', 'id', $model->id])->one()) {
                            $auth->revoke($Role, $uid);
                        }

                        if ($model->uid && !$auth->getAssignment($Role->name, $model->uid)) {
                            $auth->assign($Role, $model->uid);
                        }
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', '修改成功。');
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '修改失败。');
                }
            }
        }

        return $this->render('teacher-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTeacherDelete($id) {
        $model = Teacher::findOne($id);
        if ($model !== null) {
            if ($model->uid) {
                $auth = Yii::$app->authManager;
                $Role = $auth->getRole('teacher');

                if (!Teacher::find()->where(['uid' => $model->uid])->andWhere(['<>', 'id', $model->id])->one()) {
                    $auth->revoke($Role, $model->uid);
                }
            }
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionTeacherBind($id) {
        $model = Teacher::findOne($id);
        $uid = $model->uid;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);

                if ((int) $model->uid != $uid) {
                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole('teacher');
                    if (!Teacher::find()->where(['uid' => $uid])->andWhere(['<>', 'id', $model->id])->one()) {
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
