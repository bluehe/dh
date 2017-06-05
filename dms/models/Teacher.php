<?php

namespace dms\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $gender
 * @property integer $college
 * @property string $tel
 * @property string $email
 * @property string $address
 * @property string $note
 * @property integer $stat
 *
 * @property User $u
 * @property College $college0
 */
class Teacher extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;
    const STAT_CHECK = 3;
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'college', 'stat'], 'integer'],
            [['name', 'tel'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 8, 'message' => '{attribute}最长8个字符'],
            [['tel', 'email'], 'string', 'max' => 64, 'message' => '{attribute}最长64个字符'],
            [['address', 'note'], 'string', 'max' => 255, 'message' => '{attribute}最长255个字符'],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['college'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['college' => 'id']],
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
            'gender' => '性别',
            'college' => '学院',
            'tel' => '电话',
            'email' => '电子邮箱',
            'address' => '地址',
            'note' => '备注',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
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

    //得到ID-name 键值数组
    public static function get_user_id() {
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        return ArrayHelper::map($users, 'id', 'username');
    }

}
