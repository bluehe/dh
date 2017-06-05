<?php

namespace dms\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use dms\models\Forum;
use dms\models\Parameter;
use dms\models\Room;
use dms\models\RoomSearch;
use dms\models\Bed;
use dms\models\BedSearch;

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
//            return $this->redirect(['floor-update', 'id' => $model->id]);
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

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all forum models.
     * @return mixed
     */
    public function actionForum() {

        $dataProvider = new ActiveDataProvider([
            'query' => Forum::find()->joinWith('parent'),
            'sort' => ['defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ],
//                'attributes' => ['sort_order', 'name']
            ],
        ]);
        $sort = $dataProvider->getSort();
        $sort->attributes['sort_order'] = [
            'asc' => ['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC],
            'desc' => ['fsort' => SORT_DESC, 'mark' => SORT_DESC, 'fup' => SORT_ASC, 'sort_order' => SORT_DESC, 'id' => SORT_DESC],
        ];
        $sort->attributes['name'] = [
            'asc' => ['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'name' => SORT_ASC],
            'desc' => ['fsort' => SORT_DESC, 'mark' => SORT_DESC, 'fup' => SORT_ASC, 'name' => SORT_DESC],
        ];
        $sort->attributes['stat'] = [
            'asc' => ['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'stat' => SORT_ASC],
            'desc' => ['fsort' => SORT_DESC, 'mark' => SORT_DESC, 'fup' => SORT_ASC, 'stat' => SORT_DESC],
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
//        $model->mold = 1;
        $model->stat = Forum::STAT_OPEN;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $model->mark = $model->fup ? $model->fup : $model->id;
                $model->fsort = $model->fup ? $model->parent->sort_order : $model->sort_order;
                $model->save(false);
                $transaction->commit();
                Yii::$app->session->setFlash('success', '创建成功。');
                return $this->redirect(['forum-update', 'id' => $model->id]);
            } catch (\Exception $e) {

                $transaction->rollBack();
                //throw $e;
                Yii::$app->session->setFlash('error', '创建失败。');
            }
        }
        return $this->render('forum-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing forum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionForumUpdate($id) {
        $model = Forum::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->mark = $model->fup ? $model->fup : $model->id;
            $model->fsort = $model->fup ? $model->parent->sort_order : $model->sort_order;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                if ($model->fup) {
                    Forum::updateAll(['fup' => $model->fup, 'fsort' => $model->fsort, 'mark' => $model->mark], ['fup' => $model->id]);
                } else {
                    Forum::updateAll(['fsort' => $model->sort_order], ['fup' => $model->id]);
                }

                if ($model->stat == Forum::STAT_CLOSE) {
                    Forum::updateAll(['stat' => Forum::STAT_CLOSE], ['fup' => $model->id]);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', '修改成功。');
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '修改失败。');
            }
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

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionRoom() {

        $searchModel = new RoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('room', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Room model.
     * @return mixed
     */
    public function actionRoomCreate() {
        $model = new Room();
        $model->stat = Room::STAT_OPEN;

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
            $model->rid ? $model->setScenario("mul") : $model->setScenario("sig");
            $rids = $model->rid ? $model->rid : array(0 => NULL);
            $transaction = Yii::$app->db->beginTransaction(); //事务无效
            try {
                foreach ($rids as $rid) {
                    if (count($brooms) > 0) {
                        foreach ($brooms as $broom) {
                            $_model = clone $model;
                            $_model->rid = $rid;
                            $_model->name = (string) $broom;
                            $_model->fname = $_model->rid ? $_model->parent->name : $_model->name;
                            if (!$_model->save()) {
                                throw new \Exception("创建失败");
                            }
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', '创建成功。');
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '创建失败。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('room-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Room model.
     * @param integer $id
     * @return mixed
     */
    public function actionRoomUpdate($id) {
        $model = Room::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->rid ? $model->setScenario("mul") : $model->setScenario("sig");
            if ($model->validate()) {
                $model->fname = $model->rid ? $model->parent->name : $model->name;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    if (!$model->rid) {
                        //大室平调,小室需要变更楼苑、楼层、标志;大室变小室，有下属小室,不准变更,否则本步不需要处理
                        Room::updateAll(['fname' => $model->name, 'fid' => $model->fid, 'floor' => $model->floor, 'gender' => $model->gender], ['rid' => $model->id]);
                    }
                    if ($model->stat == Room::STAT_CLOSE) {
                        Room::updateAll(['stat' => Room::STAT_CLOSE, 'note' => $model->note], ['rid' => $model->id]);
                    }


                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '修改成功。');
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '修改失败。');
                }
                return $this->redirect(Yii::$app->session->get('room_url'));
            }
        }
        Yii::$app->session->set('room_url', Yii::$app->request->referrer);
        return $this->render('room-update', ['model' => $model,]);
    }

    /**
     * Deletes an existing Room model.
     * @param integer $id
     * @return mixed
     */
    public function actionRoomDelete($id) {
        $model = Room::findOne($id);
        if ($model !== null) {
            $model->delete();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Room model.
     * @param string $ids
     * @return mixed
     */
    public function actionRoomDeletes($ids) {
        return Room::deleteAll(['id' => explode(',', $ids)]);
    }

    /**
     * Function output the site that you selected.
     * @param int $fid
     * @param int $floor
     */
    public function actionBroomList($fid, $floor, $new = true, $id = NULL) {
        //$new更新允许选择无，$id排除上级为自己
        $model = new Room();
        $broom = $model->getBroomList($fid, $floor, $id);
        $str = '';
        if (!$new) {
            $str = Html::tag('option', '无', array('value' => ''));
        }
        foreach ($broom as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), array('value' => $value));
        }
        return $str;
    }

    /**
     * Lists all Bed models.
     * @return mixed
     */
    public function actionBed() {

        $searchModel = new BedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('bed', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Bed model.
     * @return mixed
     */
    public function actionBedCreate() {
        $model = new Bed();
        $model->stat = Bed::STAT_OPEN;

        if ($model->load(Yii::$app->request->post())) {
//            if (!$model->rid) {
//                $this->addError($model, 'rid', '房间不能为空');
//            }
            $br1 = explode("~", $model->name);
            $br2 = explode(',', $model->name);
            $beds = [];
            if (count($br1) > 1) {
                //用~分隔
                for ($a = $br1[0]; $a <= $br1[1]; $a++) {
                    $beds[] = $a;
                }
            } else {
                $beds = $br2;
            }
            //添加数据
            $rids = $model->rid ? $model->rid : array(0 => NULL);
            $transaction = Yii::$app->db->beginTransaction(); //事务无效
            try {
                foreach ($rids as $rid) {
                    if (count($beds) > 0) {
                        foreach ($beds as $bed) {
                            $_model = clone $model;
                            $_model->rid = $rid;
                            $_model->name = (string) $bed;
                            if (!$_model->save()) {
                                throw new \Exception("创建失败");
                            }
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', '创建成功。');
            } catch (\Exception $e) {

                $transaction->rollBack();
//                throw $e;
                Yii::$app->session->setFlash('error', '创建失败。');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('bed-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bed model.
     * @param integer $id
     * @return mixed
     */
    public function actionBedUpdate($id) {
        $model = Bed::findOne($id);
        $model->fid = $model->room->fid;
        $model->flid = $model->room->floor;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '修改成功。');
            } else {
                Yii::$app->session->setFlash('error', '修改失败。');
            }
            return $this->redirect(Yii::$app->session->get('bed_url'));
        }
        Yii::$app->session->set('bed_url', Yii::$app->request->referrer);
        return $this->render('bed-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bed model.
     * @param integer $id
     * @return mixed
     */
    public function actionBedDelete($id) {
        $model = Bed::findOne($id);
        if ($model !== null) {
            $model->delete();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Bed model.
     * @param string $ids
     * @return mixed
     */
    public function actionBedDeletes($ids) {
        return Bed::deleteAll(['id' => explode(',', $ids)]);
    }

    /**
     * Function output the site that you selected.
     * @param int $fid
     * @param int $floor
     */
    public function actionRoomList($fid, $floor) {

        $model = new Bed();
        $room = $model->getRoomList($fid, $floor);
        $str = '';
        foreach ($room as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), array('value' => $value));
        }
        return $str;
    }

}
