<?php

namespace dms\controllers;

use Yii;
use dms\models\RepairOrder;
use dms\models\System;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

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
        if ($model->load(Yii::$app->request->post())) {

            $str = 'BX' . date('ymd', time());
            $serial = $model->find()->where(['like', 'serial', $str])->select(['serial'])->orderBy(['serial' => SORT_DESC])->scalar();
            $model->serial = $serial ? ++$serial : $str . '001';

            $model->uid = Yii::$app->user->identity->id;
            $model->created_at = time();
            $model->stat = RepairOrder::STAT_OPEN;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '报修成功。');
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

        if ($model->load(Yii::$app->request->post())) {

            $model->stat = RepairOrder::STAT_EVALUATE;
            $model->end_at = time();


            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功。');
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

}
