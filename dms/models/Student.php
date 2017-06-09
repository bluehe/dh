<?php

namespace dms\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%student}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $stuno
 * @property string $gender
 * @property integer $college
 * @property integer $major
 * @property integer $grade
 * @property integer $teacher
 * @property string $tel
 * @property string $email
 * @property string $address
 * @property string $note
 * @property integer $stat
 *
 * @property User $u
 * @property College $college0
 * @property Major $major0
 * @property Parameter $grade0
 * @property Teacher $teacher0
 */
class Student extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_GRADUATE = -1;
    const STAT_CLOSE = 2;
    const STAT_CHECK = 3;
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%student}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'college', 'major', 'grade', 'teacher', 'stat'], 'integer'],
            [['name'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 8, 'message' => '{attribute}最长8个字符'],
            [['stuno'], 'string', 'max' => 16, 'message' => '{attribute}最长16个字符'],
            [['tel', 'email'], 'string', 'max' => 64],
            [['address', 'note'], 'string', 'max' => 255],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['college'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['college' => 'id']],
            [['major'], 'exist', 'skipOnError' => true, 'targetClass' => Major::className(), 'targetAttribute' => ['major' => 'id']],
            [['grade'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::className(), 'targetAttribute' => ['grade' => 'id']],
            [['teacher'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
            [['stat'], 'in', 'range' => [self::STAT_OPEN, self::STAT_CLOSE]],
            [['gender'], 'default', 'value' => self::GENDER_MALE],
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户',
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
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "在读",
            self::STAT_GRADUATE => "毕业",
            self::STAT_CLOSE => " ",
            self::STAT_CHECK => "待审核"
        ],
        'gender' => [
            self::GENDER_MALE => "男",
            self::GENDER_FEMALE => "女"
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getGender() {
        return isset(self::$List['gender'][$this->gender]) ? self::$List['gender'][$this->gender] : null;
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
    public function getCollege0() {
        return $this->hasOne(College::className(), ['id' => 'college']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMajor0() {
        return $this->hasOne(Major::className(), ['id' => 'major']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrade0() {
        return $this->hasOne(Parameter::className(), ['id' => 'grade']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher0() {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher']);
    }

    public function getBed() {
        $order = CheckOrder::find()->where(['related_id' => $this->id, 'related_table' => CheckOrder::TABLE_STUDENT, 'stat' => [CheckOrder::STAT_CHECKWAIT, CheckOrder::STAT_CHECKIN]])->one();

        if ($order !== null) {
            return $order->bed0->AllName;
        }
        return null;
    }

    //得到ID-name 键值数组
    public static function get_user_id() {
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        return ArrayHelper::map($users, 'id', 'username');
    }

    public static function get_major() {
        $mids = static::find()->select(['major'])->distinct()->column();
        $types = Major::find()->where(['id' => $mids])->all();
        return ArrayHelper::map($types, 'id', 'name');
    }

    public static function get_grade() {
        $gids = static::find()->select(['grade'])->distinct()->column();
        $types = Parameter::find()->where(['name' => 'grade', 'id' => $gids])->all();
        return ArrayHelper::map($types, 'id', 'v');
    }

    public static function get_teacher() {
        $tids = static::find()->select(['teacher'])->distinct()->column();
        $types = Teacher::find()->where(['id' => $tids])->all();
        return ArrayHelper::map($types, 'id', 'name');
    }

    //通过中文名得到性别代号
    public static function get_id_gender($gender) {

        return arry_search($gender, self::$List['gender']);
    }

    //通过名称得到学院id
    public static function get_id_college($college) {
        $result = College::find()->where(['name' => $college])->select(['id'])->scalar();
        return $result ? $result : NULL;
    }

    //通过名称得到专业id
    public static function get_id_major($major, $cid = NULL) {
        $result = Major::find()->where(['name' => $major])->andFilterWhere(['college' => $cid])->select(['id'])->scalar();
        return $result ? $result : NULL;
    }

    //通过名称得到教师id
    public static function get_id_teacher($teacher, $cid = NULL) {
        $result = Teacher::find()->where(['name' => $teacher])->andFilterWhere(['college' => $cid])->select(['id'])->scalar();
        return $result ? $result : NULL;
    }

    //通过名称得到年级id
    public static function get_id_grade($grade) {
        $result = Parameter::find()->where(['name' => 'grade', 'v' => $grade])->select(['id'])->scalar();
        return $result ? $result : NULL;
    }

}
