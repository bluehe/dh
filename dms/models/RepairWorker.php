<?php

namespace dms\models;

use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%repair_worker}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $cid
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $address
 * @property string $note
 * @property integer $stat

 */
class RepairWorker extends \yii\db\ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;
    const ROLE_ADMIN = 1;
    const ROLE_WORKER = 2;
    const DAY_SUN = 1;
    const DAY_MON = 2;
    const DAY_TUE = 3;
    const DAY_WED = 4;
    const DAY_THU = 5;
    const DAY_FRI = 6;
    const DAY_SAT = 7;

    public $type;
    public $area;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%repair_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'unit_id', 'role', 'stat'], 'integer'],
            [['name', 'tel', 'role'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 8, 'message' => '{attribute}最长8个字符'],
            [['tel', 'email'], 'string', 'max' => 64, 'message' => '{attribute}最长64个字符'],
            ['email', 'email', 'message' => '{attribute}格式错误'],
            [['address', 'workday', 'note'], 'string', 'max' => 255],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => RepairUnit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
            [['stat'], 'in', 'range' => [self::STAT_OPEN, self::STAT_CLOSE]],
            [['role'], 'default', 'value' => self::ROLE_WORKER],
            [['role'], 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_WORKER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户',
            'unit_id' => '单位',
            'name' => '姓名',
            'tel' => '电话',
            'email' => '电子邮箱',
            'address' => '地址',
            'workday' => '派工周期',
            'note' => '备注',
            'stat' => '状态',
            'type' => '类型',
            'role' => '角色',
            'area' => '区域',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
        ],
        'role' => [
            self::ROLE_ADMIN => '审核员',
            self::ROLE_WORKER => '维修工'
        ],
        'workday' => [
            self::DAY_MON => '一',
            self::DAY_TUE => '二',
            self::DAY_WED => '三',
            self::DAY_THU => '四',
            self::DAY_FRI => '五',
            self::DAY_SAT => '六',
            self::DAY_SUN => '日'
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getRole() {

        $role = self::$List['role'][$this->role];
        return isset($role) ? $role : null;
    }

    public function getWorkday() {
        $days = explode(',', $this->workday);
        foreach ($days as $day) {
            if (isset(self::$List['workday'][$day])) {
                $d[] = self::$List['workday'][$day];
            }
        }
        return isset($d) ? implode(',', $d) : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit() {
        return $this->hasOne(RepairUnit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkerAreas() {
        return $this->hasMany(RepairWorkerArea::className(), ['worker' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas() {
        return $this->hasMany(Forum::className(), ['id' => 'area'])->viaTable('{{%repair_worker_area}}', ['worker' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkerTypes() {
        return $this->hasMany(RepairWorkerType::className(), ['worker' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes() {
        return $this->hasMany(Parameter::className(), ['id' => 'type'])->viaTable('{{%repair_worker_type}}', ['worker' => 'id']);
    }

    //得到ID-name 键值数组
    public static function get_unit_id() {
        $units = RepairUnit::find()->where(['stat' => RepairUnit::STAT_OPEN])->all();
        return ArrayHelper::map($units, 'id', 'name');
    }

    //得到ID-name 键值数组
    public static function get_type_id($id = array()) {
        $types = Parameter::find()->where(['name' => 'repair_type'])->andFilterWhere(['id' => $id])->all();
        return ArrayHelper::map($types, 'id', 'v');
    }

    //得到ID-name 键值数组
    public static function get_user_id() {
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        return ArrayHelper::map($users, 'id', 'username');
    }

    //得到数组
    public static function get_worker_type($worker) {
        return RepairWorkerType::find()->where(['worker' => $worker])->select(['type'])->column();
    }

    //得到数组
    public static function get_worker_area($worker) {
        return RepairWorkerArea::find()->where(['worker' => $worker])->select(['area'])->column();
    }

}
