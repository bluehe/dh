<?php

namespace dms\controllers;

use Yii;
use dms\models\RepairOrder;
use dms\models\System;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use dms\models\Pickup;
use dms\models\RepairWorker;
use dms\models\Suggest;

/**
 * BusinessController implements the CRUD actions for RepairOrder model.
 */
class BusinessController extends Controller {

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
    public function actionRepairBusiness() {

        $dataProvider = new ActiveDataProvider([
            'query' => RepairOrder::find()->joinWith('type')->joinWith('area')->andWhere(['uid' => Yii::$app->user->identity->id]),
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);
        return $this->render('repair-business', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RepairOrder model.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairView($id) {
        $model = RepairOrder::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id]);
        if ($model !== null) {
            return $this->renderAjax('repair-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Creates a new RepairOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRepairCreate() {
        $model = new RepairOrder();
        if (System::getValue('business_repair') === '1') {
            $model->setScenario('repair');
        }
        //默认姓名和电话
        $repair = RepairOrder::find()->where(['uid' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->one();
        if ($repair !== null) {
            $model->name = $repair->name;
            $model->tel = $repair->tel;
            $model->repair_area = $repair->repair_area;
        }

        if ($model->load(Yii::$app->request->post())) {

            $str = 'BX' . date('ymd', time());
            $serial = $model->find()->where(['like', 'serial', $str])->select(['serial'])->orderBy(['serial' => SORT_DESC])->scalar();
            $model->serial = $serial ? ++$serial : $str . '001';

            $model->uid = Yii::$app->user->identity->id;
            $model->created_at = time();
            $model->stat = RepairOrder::STAT_OPEN;
            //后期扩展
//            if (System::getValue('business_action') === '1') {
//                $model->stat = RepairOrder::STAT_OPEN;
//            } else if (System::getValue('business_action') === '2') {
//                //自动受理
//                $model->stat = RepairOrder::STAT_ACCEPT;
//            } else if (System::getValue('business_action') === '3') {
//                //自动派工
//                $model->stat = RepairOrder::STAT_DISPATCH;
//            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '报修成功。');
                //微信模板消息
                if (System::existValue('repaire_wechat_send', '1')) {
                    $query = RepairWorker::find()->where(['role' => RepairWorker::ROLE_ADMIN]);
                    if ($model->repair_area) {
                        $query->joinWith('workerAreas')->andWhere(['area' => $model->repair_area]);
                    }
                    if ($model->repair_type) {
                        $query->joinWith('workerTypes')->andWhere(['type' => $model->repair_type]);
                    }
                    $user = $query->select(['uid'])->distinct()->column();
                    Yii::$app->commonHelper->sendWechatTemplate($user, 'repaire_create', $model);
                }
                return $this->redirect(['repair-business']);
            } else {
                Yii::$app->session->setFlash('error', '报修失败。');
            }
        }
        return $this->render('repair-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RepairOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRepairUpdate($id) {
        $model = RepairOrder::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => RepairOrder::STAT_OPEN]);
        if (System::getValue('business_repair') === '1') {
            $model->setScenario('repair');
        }
        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->render('repair-update', [
                        'model' => $model,
            ]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRepairClose($id) {
        $model = RepairOrder::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => RepairOrder::STAT_OPEN]);

        if ($model !== null) {
            $model->stat = RepairOrder::STAT_CLOSE;
            $model->end_at = time();
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRepairEvaluate($id) {
        $model = RepairOrder::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => RepairOrder::STAT_REPAIRED]);
        $model->evaluate1 = RepairOrder::EVALUATE_VSAT;
        $model->evaluate2 = RepairOrder::EVALUATE_VSAT;
        $model->evaluate3 = RepairOrder::EVALUATE_VSAT;

        if ($model->load(Yii::$app->request->post())) {

            $model->stat = RepairOrder::STAT_EVALUATE;
            $model->evaluate = RepairOrder::EVALUATE_USER;
            $model->end_at = time();


            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
                Yii::$app->commonHelper->sendWechatTemplate(array($model->accept_uid, RepairWorker::getUid($model->worker_id)), 'repaire_evaluate', $model);
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('repair-evaluate', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /**
     * Lists all Pickup models.
     * @return mixed
     */
    public function actionPickupBusiness() {

        $dataProvider = new ActiveDataProvider([
            'query' => Pickup::find()->andWhere(['uid' => Yii::$app->user->identity->id]),
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);
        return $this->render('pickup-business', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Pickup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPickupCreate() {
        $model = new Pickup();

        //默认姓名和电话
        $pickup = Pickup::find()->where(['uid' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->one();
        if ($pickup !== null) {
            $model->name = $pickup->name;
            $model->tel = $pickup->tel;
        }
        if ($model->load(Yii::$app->request->post())) {

            $model->uid = Yii::$app->user->identity->id;
            $model->created_at = time();
            $model->stat = Pickup::STAT_OPEN;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '发布成功。');
                return $this->redirect(['pickup-business']);
            } else {
                Yii::$app->session->setFlash('error', '发布失败。');
            }
        }
        return $this->render('pickup-create', [
                    'model' => $model,
        ]);
    }

    public function actionPickupUpdate($id) {
        $model = Pickup::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Pickup::STAT_OPEN]);

        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->render('pickup-update', [
                        'model' => $model,
            ]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPickupClose($id, $stat) {
        $model = Pickup::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Pickup::STAT_OPEN]);

        if ($model !== null) {
            $model->end_uid = Yii::$app->user->identity->id;
            $model->stat = $stat;
            $model->end_at = time();
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPickupView($id) {
        $model = Pickup::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id]);
        if ($model !== null) {
            return $this->renderAjax('pickup-view', [
                        'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('error', '没有权限。');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Lists all Suggest models.
     * @return mixed
     */
    public function actionSuggestBusiness() {

        $dataProvider = new ActiveDataProvider([
            'query' => Suggest::find()->andWhere(['uid' => Yii::$app->user->identity->id]),
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);
        return $this->render('suggest-business', [
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

        //默认姓名和电话
        $suggest = Suggest::find()->where(['uid' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->one();
        if ($suggest !== null) {
            $model->name = $suggest->name;
            $model->tel = $suggest->tel;
        }
        if ($model->load(Yii::$app->request->post())) {

            $str = 'T' . date('ymd', time());
            $serial = $model->find()->where(['like', 'serial', $str])->select(['serial'])->orderBy(['serial' => SORT_DESC])->scalar();
            $model->serial = $serial ? ++$serial : $str . '001';

            $model->uid = Yii::$app->user->identity->id;
            $model->created_at = time();
            $model->stat = Suggest::STAT_OPEN;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '发布成功。');
                return $this->redirect(['suggest-business']);
            } else {
                Yii::$app->session->setFlash('error', '发布失败。');
            }
        }
        return $this->render('suggest-create', [
                    'model' => $model,
        ]);
    }

    public function actionSuggestUpdate($id) {
        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Suggest::STAT_OPEN]);

        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->render('suggest-update', [
                        'model' => $model,
            ]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSuggestClose($id) {
        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Suggest::STAT_OPEN]);

        if ($model !== null) {
            $model->stat = Suggest::STAT_CLOSE;
            $model->end_at = time();
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

    public function actionSuggestEvaluate($id) {
        $model = Suggest::findOne(['id' => $id, 'uid' => Yii::$app->user->identity->id, 'stat' => Suggest::STAT_REPLY]);
        $model->evaluate1 = Suggest::EVALUATE_VSAT;
        if ($model->load(Yii::$app->request->post())) {

            $model->stat = Suggest::STAT_EVALUATE;
            $model->evaluate = Suggest::EVALUATE_USER;
            $model->end_at = time();


            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
                Yii::$app->commonHelper->sendWechatTemplate($model->reply_uid, 'suggest_evaluate', $model);
            } else {
                Yii::$app->session->setFlash('error', '操作失败。');
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model !== null) {
                return $this->renderAjax('suggest-evaluate', [
                            'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', '没有权限。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

}
