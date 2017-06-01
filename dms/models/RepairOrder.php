<?php

namespace dms\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%repair_order}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $repair_type
 * @property integer $repair_area
 * @property string $address
 * @property string $title
 * @property string $content
 * @property integer $evaluate
 * @property integer $created_at
 * @property integer $accept_at
 * @property integer $accept_uid
 * @property integer $repair_at
 * @property integer $worker_id
 * @property integer $end_at
 * @property integer $stat
 *
 */
class RepairOrder extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = -2;
    const STAT_ACCEPT = 2;
    const STAT_NO_ACCEPT = -1;
    const STAT_DISPATCH = 3;
    const STAT_REPAIRED = 4;
    const STAT_EVALUATE = 5;
    const EVALUATE_VSAT = 5;
    const EVALUATE_SAT = 4;
    const EVALUATE_COM = 3;
    const EVALUATE_DIS = 2;
    const EVALUATE_VDIS = 1;
    const EVALUATE_SYSTEM = 1;
    const EVALUATE_USER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%repair_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'repair_type', 'repair_area', 'evaluate1', 'evaluate2', 'evaluate3', 'created_at', 'accept_at', 'accept_uid', 'dispatch_at', 'dispatch_uid', 'repair_at', 'repair_uid', 'worker_id', 'end_at', 'evaluate', 'stat'], 'integer'],
            [['address', 'content', 'created_at', 'serial', 'name', 'tel'], 'required', 'message' => '{attribute}不能为空'],
            [['worker_id'], 'required', 'message' => '{attribute}不能为空', 'on' => 'dispatch'],
            [['repair_type', 'repair_area'], 'required', 'message' => '{attribute}不能为空', 'on' => 'repair'],
            [['serial'], 'string', 'max' => 16, 'message' => '{attribute}最长16个字符'],
            [['address', 'title', 'content', 'note'], 'string', 'max' => 250, 'message' => '{attribute}最长250个字符'],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => RepairWorker::className(), 'targetAttribute' => ['worker_id' => 'id']],
            [['repair_type'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::className(), 'targetAttribute' => ['repair_type' => 'id']],
            [['repair_area'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::className(), 'targetAttribute' => ['repair_area' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '报修人',
            'name' => '报修人',
            'tel' => '联系电话',
            'serial' => '编号',
            'repair_type' => '类型',
            'repair_area' => '区域',
            'address' => '详细地址',
            'title' => '标题',
            'content' => '内容',
            'evaluate1' => '响应速度',
            'evaluate2' => '服务态度',
            'evaluate3' => '服务质量',
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
            'evaluate' => '评价人',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "等待受理",
            self::STAT_CLOSE => "取消",
            self::STAT_ACCEPT => "已受理",
            self::STAT_NO_ACCEPT => "未受理",
            self::STAT_DISPATCH => "已派工",
            self::STAT_REPAIRED => "已维修",
            self::STAT_EVALUATE => "已评价",
        ],
        'evaluate' => [
            self::EVALUATE_VSAT => '非常满意',
            self::EVALUATE_SAT => '满意',
            self::EVALUATE_COM => '一般',
            self::EVALUATE_DIS => '不满意',
            self::EVALUATE_VDIS => '非常不满意',
        ],
        'evaluate_user' => [
            self::EVALUATE_SYSTEM => '系统',
            self::EVALUATE_USER => '用户',
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getEvaluate1() {

        $evaluate = self::$List['evaluate'][$this->evaluate1];
        return isset($evaluate) ? $evaluate : null;
    }

    public function getEvaluate2() {

        $evaluate = self::$List['evaluate'][$this->evaluate2];
        return isset($evaluate) ? $evaluate : null;
    }

    public function getEvaluate3() {

        $evaluate = self::$List['evaluate'][$this->evaluate3];
        return isset($evaluate) ? $evaluate : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser($uid) {
        return User::findIdentity($uid);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorker() {
        return $this->hasOne(RepairWorker::className(), ['id' => 'worker_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(Parameter::className(), ['id' => 'repair_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea() {
        return $this->hasOne(Forum::className(), ['id' => 'repair_area']);
    }

    public static function get_repair_type() {
        $tids = static::find()->select(['repair_type'])->distinct()->column();
        $types = Parameter::find()->where(['name' => 'repair_type', 'id' => $tids])->all();
        return ArrayHelper::map($types, 'id', 'v');
    }

    public static function get_repair_area() {
        $fids = static::find()->select(['repair_area'])->distinct()->column();
        $forum = Forum::find()->where(['id' => $fids])->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    public static function get_repair_worker() {
        $workers = static::find()->select(['worker_id'])->distinct()->column();
        $query = RepairWorker::find()->where(['id' => $workers]);

        if (!Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            $query->andWhere(['uid' => Yii::$app->user->identity->id]);
        }
        $worker = $query->all();
        return ArrayHelper::map($worker, 'id', 'name');
    }

    /**
     * @param $pid
     * @return array
     */
    public function getWorkerList($type = array(), $area = array()) {

        $query = RepairWorker::find()->where(['stat' => RepairWorker::STAT_OPEN])->andWhere("find_in_set(:workday,workday)", [':workday' => date('w', time()) + 1]);
        if (System::getValue('business_dispatch') === '2') {
            $tids = RepairWorkerType::find()->where(['type' => $type])->select(['worker'])->column();
            $aids = RepairWorkerArea::find()->where(['area' => $area])->select(['worker'])->column();
            $query->andFilterWhere(['id' => $tids]);
            $query->andFilterWhere(['id' => $aids]);
        }
        $workers = $query->all();
        return ArrayHelper::map($workers, 'id', 'name');
    }

    public static function get_repair_today($t = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }

        return $query->andFilterWhere(['>=', 'created_at', $t])->count();
    }

    public static function get_stat_total() {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        return $query->groupBy(['stat'])->select(['count(*)'])->indexBy('stat')->column();
    }

    public static function get_area_total($t = '', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $query->andFilterWhere(['repair_type' => $t])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<', 'created_at', $end]);
        return $query->groupBy(['repair_area'])->select(['count(*)'])->indexBy('repair_area')->column();
    }

    public static function get_type_total($a = '', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $query->andFilterWhere(['repair_area' => $a])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end]);
        return $query->groupBy(['repair_type'])->select(['count(*)'])->indexBy('repair_type')->column();
    }

    public static function get_evaluate_total($a = 'evaluate1', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $query->andWhere(['not', [$a => NULL]])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end]);
        return $query->groupBy([$a])->select(['count(*)'])->indexBy($a)->column();
    }

    public static function get_evaluate_avg($a = 'evaluate1', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }
        $query->andWhere(['not', [$a => NULL]])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end]);
        return $query->groupBy(["FROM_UNIXTIME(created_at, '%Y-%m-%d')"])->select(['avg(' . $a . ')'])->indexBy("FROM_UNIXTIME(created_at, '%Y-%m-%d')")->column();
    }

    public static function get_day_total($a = 'created_at', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }

        $query->andFilterWhere(['>=', $a, $start])->andFilterWhere(['<=', $a, $end]);
        return $query->groupBy(["FROM_UNIXTIME($a, '%Y-%m-%d')"])->select(['count(*)'])->indexBy("FROM_UNIXTIME($a, '%Y-%m-%d')")->column();
    }

    public static function get_work_total($a = 'accept_at', $b = 'accept_uid', $start = '', $end = '') {

        $query = static::find()->where(['not', ['stat' => self::STAT_CLOSE]]);
        if (!Yii::$app->user->can('日常事务') && !Yii::$app->user->can('报修管理')) {
//维修工
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        } elseif (!Yii::$app->user->can('日常事务') && Yii::$app->user->can('报修管理')) {
//受理员
            $type = RepairWorker::get_worker_type(Yii::$app->user->identity->id);
            $area = RepairWorker::get_worker_area(Yii::$app->user->identity->id);
            $query->andWhere(['OR', ['repair_type' => NULL], ['repair_type' => $type]])->andWhere(['OR', ['repair_area' => NULL], ['repair_area' => $area]]);
        }

        $query->andFilterWhere(['>=', $a, $start])->andFilterWhere(['<=', $a, $end]);
        return $query->groupBy([$b])->select(['count(*)'])->indexBy($b)->column();
    }

}
