<?php

namespace dms\controllers;

use Yii;
use yii\helpers\Url;
use yii\imagine\Image;
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
                    $param['url'] = Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['work/repair-work', 'RepairOrderSearch[stat]' => RepairOrder::STAT_OPEN], true)], true);
                    $param['first'] = '您好，您有新的报修单';
                    Yii::$app->commonHelper->sendWechatTemplate($user, 'repaire_user', $param, $model);
                }
                return $this->redirect(['repair-business']);
            } else {
                Yii::$app->session->setFlash('error', '报修失败。');
            }
        }
        $postMaxSize = ini_get('post_max_size');
        $fileMaxSize = ini_get('upload_max_filesize');
        $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;
        switch (substr($displayMaxSize, -1)) {
            case 'G':
                $displayMaxSize = $displayMaxSize * 1024 * 1024;
            case 'M':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'K':
                $displayMaxSize = $displayMaxSize;
        }
        return $this->render('repair-create', [
                    'model' => $model,
                    'maxsize' => $displayMaxSize,
        ]);
    }

    /*
     * ajax方式文件上传
     * 输入$_FILES['files']和dir
     * @return json_encode(['error'=>'']）
     */

    public function actionUploadImage() {

        //判断是否Ajax
        if (Yii::$app->request->isAjax) {

            if (empty($_FILES['RepairOrder']['name']['images'])) {
                $postMaxSize = ini_get('post_max_size');
                $fileMaxSize = ini_get('upload_max_filesize');
                $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;

                return json_encode(['error' => '没有文件上传,文件最大为' . $displayMaxSize], JSON_UNESCAPED_UNICODE);
                // or you can throw an exception
            }

            //获得文件夹
            $dir = Yii::$app->request->post('dir') ? Yii::$app->request->post('dir') : 'tmp';

            //目标文件夹，不存在则创建
            $targetFolder = '/data/' . $dir;
            $targetPath = Yii::getAlias('@webroot') . $targetFolder;
            $targetUrl = Yii::getAlias('@web') . $targetFolder;

            if (!file_exists($targetPath)) {
                @mkdir($targetPath, 0777, true);
            }

            $files = $_FILES['RepairOrder']['name']['images'];
            // a flag to see if everything is ok
            $success = null;

            // file paths to store
            $paths = [];
            //访问路径
            $urls = [];
            //文件名
            $file_names = [];

            // get file names
            $filenames = $files;

            // loop and process files
            for ($i = 0; $i < count($filenames); $i++) {
                $ext = explode('.', basename($filenames[$i]));
                $f_name = md5(uniqid()) . "." . strtolower(array_pop($ext));
                $filename = $targetPath . DIRECTORY_SEPARATOR . $f_name;
                $url = $targetUrl . '/' . $f_name;

                //文件存在则删除
                if (file_exists($filename)) {
                    @unlink($filename);
                }
                if (@move_uploaded_file($_FILES['RepairOrder']['tmp_name']['images'][$i], $filename)) {
                    Image::resize($filename, 200, 200, true, true)->save($filename);
                    $success = true;
                    $paths[] = $filename;
                    $urls[] = $url;
                    $file_names[] = $f_name;
                } else {
                    $success = false;
                    break;
                }
            }
            if ($success === true) {
                $output = ['urls' => $urls, 'paths' => $paths, 'file_names' => $file_names];
            } elseif ($success === false) {
                $output = ['error' => '文件上传出错！'];
                // delete any uploaded files
                foreach ($paths as $file) {
                    @unlink($file);
                }
            }
            return json_encode($output, JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['error' => '上传错误！'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actionDeleteImage() {
        $dir = Yii::$app->request->post('dir') ? Yii::$app->request->post('dir') : 'tmp';
        $targetFolder = '/data/' . $dir;
        $targetPath = Yii::getAlias('@webroot') . $targetFolder;
        // 前面我们已经为成功上传的banner图指定了key
        if ($name = Yii::$app->request->post('key')) {

        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
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
                $param['url'] = Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['work/repair-work', 'RepairOrderSearch[stat]' => RepairOrder::STAT_EVALUATE], true)], true);
                $param['first'] = '您好，您收到新的评价';
                Yii::$app->commonHelper->sendWechatTemplate(array($model->accept_uid, RepairWorker::getUid($model->worker_id)), 'repaire_user', $param, $model);
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
                $param['url'] = Url::toRoute(['wechat/redirect', 'url' => Url::toRoute(['work/suggest-work', 'RepairOrderSearch[stat]' => Suggest::STAT_EVALUATE], true)], true);
                $param['first'] = '您好，您收到新的评价';
                Yii::$app->commonHelper->sendWechatTemplate($model->reply_uid, 'suggest_user', $param, $model);
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
