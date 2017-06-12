<?php

namespace dms\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use bluehe\phpexcel\Excel;
use dms\models\Student;
use dms\models\StudentSearch;
use dms\models\Major;
use dms\models\Teacher;
use dms\models\CheckOrder;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller {

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
     * Lists all Student models.
     * @return mixed
     */
    public function actionStudent() {

        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        return $this->render('student', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'maxsize' => $displayMaxSize
        ]);
    }

    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionStudentCreate() {

        $model = new Student();
        $model->stat = Student::STAT_OPEN;
        $model->gender = Student::GENDER_MALE;
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    if ($model->uid) {
                        $auth = Yii::$app->authManager;
                        $Role_new = $auth->getRole('student');
                        if (!$auth->getAssignment($Role_new->name, $model->uid)) {
                            $auth->assign($Role_new, $model->uid);
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '添加成功。');
                    return $this->redirect(['student-update', 'id' => $model->id]);
                } catch (\Exception $e) {

                    $transaction->rollBack();
//                throw $e;
                    Yii::$app->session->setFlash('error', '添加失败。');
                }
            }
        }
        return $this->render('student-create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStudentUpdate($id) {

        $model = Student::findOne($id);

        $uid = $model->uid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);

                    if ((int) $model->uid != $uid) {

                        $auth = Yii::$app->authManager;
                        $Role = $auth->getRole('student');

                        if (!Student::find()->where(['uid' => $uid])->andWhere(['<>', 'id', $model->id])->one()) {
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

        return $this->render('student-update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStudentDelete($id) {
        $model = Student::findOne($id);
        if ($model !== null) {
            if ($model->uid) {
                $auth = Yii::$app->authManager;
                $Role = $auth->getRole('student');

                if (!Student::find()->where(['uid' => $model->uid])->andWhere(['<>', 'id', $model->id])->one()) {
                    $auth->revoke($Role, $model->uid);
                }
            }
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionStudentDeletes($ids) {
        $auth = Yii::$app->authManager;
        $Role = $auth->getRole('student');
        $transaction = Yii::$app->db->beginTransaction();
        $sids = explode(',', $ids);
        $result = true;
        try {
            foreach ($sids as $id) {
                $model = Student::findOne($id);
                if ($model !== null && $model->uid) {
                    if (!Student::find()->where(['uid' => $model->uid])->andWhere(['<>', 'id', $model->id])->one()) {
                        $auth->revoke($Role, $model->uid);
                    }
                }
            }
            Student::deleteAll(['id' => $sids]);
            $transaction->commit();
        } catch (\Exception $e) {

            $transaction->rollBack();
//                throw $e;
            $result = false;
        }
        return $result;
    }

    public function actionStudentBind($id) {
        $model = Student::findOne($id);
        $uid = $model->uid;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);

                if ((int) $model->uid != $uid) {
                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole('student');
                    if (!Student::find()->where(['uid' => $uid])->andWhere(['<>', 'id', $model->id])->one()) {
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

    public function actionStudentCheckbed($id) {
        $model = CheckOrder::findOne(['related_id' => $id, 'related_table' => CheckOrder::TABLE_STUDENT, 'stat' => [CheckOrder::STAT_CHECKIN, CheckOrder::STAT_CHECKWAIT]]);
        if ($model == null) {
            $model = new CheckOrder();
            $model->related_table = CheckOrder::TABLE_STUDENT;
            $model->related_id = $id;
            $model->created_uid = Yii::$app->user->identity->id;
            $model->stat = CheckOrder::STAT_CHECKIN;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_uid = Yii::$app->user->identity->id;
            $model->save();
            Yii::$app->cache->delete('building_data');
            // return $model;
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('_form-checkbed', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Function output the site that you selected.
     * @param int $cid
     */
    public function actionMajorList($cid) {

        $model = new Major();
        $major = $model->get_college_major($cid);
        $str = Html::tag('option', Html::encode('请选择'), array('value' => ''));

        foreach ($major as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), array('value' => $value));
        }
        return $str;
    }

    /**
     * Function output the site that you selected.
     * @param int $cid
     */
    public function actionTeacherList($cid) {

        $model = new Teacher();
        $teacher = $model->get_college_teacher($cid);
        $str = Html::tag('option', Html::encode('请选择'), array('value' => ''));
        foreach ($teacher as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), array('value' => $value));
        }
        return $str;
    }

    public function actionStudentExport() {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1000);

        Excel::export([
            'models' => $dataProvider->getModels(),
            'fileName' => '学生名单(' . date('Y-m-d', time()) . ')',
            'format' => 'Excel5',
            'style' => ['font_name' => '宋体', 'font_size' => 12, 'alignment_horizontal' => 'center', 'alignment_vertical' => 'center', 'row_height' => 20],
            'headerTitle' => ['title' => '学生名单(' . date('Y-m-d', time()) . ')', 'style' => ['font_bold' => true, 'font_size' => 16, 'row_height' => 30]],
            'firstTitle' => ['font_bold' => true, 'row_height' => 20, 'from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
            'columns' => [
                ['attribute' => 'name', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'stuno', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                [
                    'attribute' => 'gender',
                    'value' =>
                    function($model) {
                        return $model->Gender;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'college',
                    'value' =>
                    function($model) {
                        return $model->college ? $model->college0->name : $model->college;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'major',
                    'value' =>
                    function($model) {
                        return $model->major ? $model->major0->name : $model->major;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'grade',
                    'value' =>
                    function($model) {
                        return $model->grade ? $model->grade0->v : $model->grade;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                [
                    'attribute' => 'teacher',
                    'value' =>
                    function($model) {
                        return $model->teacher ? $model->teacher0->name : $model->teacher;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],
                ],
                ['attribute' => 'tel', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'email', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'address', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                ['attribute' => 'note', 'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]]],],
                [
                    'attribute' => 'bed',
                    'value' =>
                    function($model) {
                        return $model->bed;   //主要通过此种方式实现
                    },
                    'style' => ['from_array' => ['borders' => ['outline' => ['style' => 'thin', 'color' => ['argb' => 'FF000000']]]], 'column_width' => 23]
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
                'name' => '姓名',
                'stuno' => '学号',
                'gender' => '性别',
                'college' => '学院',
                'major' => '专业',
                'grade' => '年级',
                'teacher' => '教师',
                'tel' => '电话',
                'email' => 'E-mail',
                'address' => '地址',
                'note' => '备注',
                'bed' => '床位',
                'stat' => '状态',
            ],
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionStudentImport() {
        //判断是否Ajax
        if (Yii::$app->request->isAjax) {

            if (empty($_FILES['files'])) {
                $postMaxSize = ini_get('post_max_size');
                $fileMaxSize = ini_get('upload_max_filesize');
                $displayMaxSize = $postMaxSize < $fileMaxSize ? $postMaxSize : $fileMaxSize;

                return json_encode(['error' => '没有文件上传,文件最大为' . $displayMaxSize], JSON_UNESCAPED_UNICODE);
                // or you can throw an exception
            }


            $datas = Excel::import($_FILES['files']['tmp_name'], ['headerTitle' => true, 'setFirstRecordAsKeys' => true,]);


            $num = 0;
            foreach ($datas[0] as $data) {
                //return json_encode($data);
                $model = new Student();
                $model->name = isset($data[$model->getAttributes('name')]) ? $data[$model->getAttributes('name')] : NULL;
                $model->stuno = isset($data[$model->getAttributes('stuno')]) ? $data[$model->getAttributes('stuno')] : NULL;
                $model->gender = isset($data[$model->getAttributes('gender')]) ? array_search($data[$model->getAttributes('gender')], Student::$List['gender']) : NULL;
                $model->college = isset($data[$model->getAttributes('college')]) ? Student::get_id_college($data[$model->getAttributes('college')]) : NULL;
                $model->major = isset($data[$model->getAttributes('major')]) ? Student::get_id_major($data[$model->getAttributes('major')], $model->college) : NULL;
                $model->grade = isset($data[$model->getAttributes('grade')]) ? Student::get_id_grade($data[$model->getAttributes('grade')]) : NULL;
                $model->teacher = isset($data[$model->getAttributes('teacher')]) ? Student::get_id_teacher($data[$model->getAttributes('teacher')]) : NULL;
                $model->tel = isset($data[$model->getAttributes('tel')]) ? $data[$model->getAttributes('tel')] : NULL;
                $model->email = isset($data[$model->getAttributes('email')]) ? $data[$model->getAttributes('email')] : NULL;
                $model->address = isset($data[$model->getAttributes('address')]) ? $data[$model->getAttributes('address')] : NULL;
                $model->note = isset($data[$model->getAttributes('note')]) ? $data[$model->getAttributes('note')] : NULL;
                $model->stat = isset($data[$model->getAttributes('stat')]) ? array_search($data[$model->getAttributes('stat')], Student::$List['stat']) : Student::STAT_CHECK;
                if ($model->save()) {
                    $num++;
                }
            }
            if ($num > 0) {
                Yii::$app->session->setFlash('success', '成功导入' . $num . '个学生信息。');
            } else {
                Yii::$app->session->setFlash('error', '没有学生信息被导入。');
            }
        } else {
            Yii::$app->session->setFlash('error', '上传失败。');
        }
        return true;
    }

}
