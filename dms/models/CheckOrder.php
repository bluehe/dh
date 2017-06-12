<?php

namespace dms\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%check_order}}".
 *
 * @property integer $id
 * @property string $related_table
 * @property integer $related_id
 * @property integer $bed
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $checkout_at
 * @property integer $created_uid
 * @property integer $updated_uid
 * @property integer $checkout_uid
 * @property integer $stat
 *
 * @property User $createdU
 * @property User $updatedU
 * @property User $checkoutU
 */
class CheckOrder extends \yii\db\ActiveRecord {

    const STAT_CHECKIN = 1;
    const STAT_CHECKOUT = 2;
    const STAT_CHECKWAIT = 3;
    const TABLE_TEACHER = 'teacher';
    const TABLE_STUDENT = 'student';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%check_order}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['related_table', 'related_id', 'bed'], 'required', 'message' => '{attribute}不能为空'],
            [['related_id', 'bed', 'created_at', 'updated_at', 'checkout_at', 'created_uid', 'updated_uid', 'checkout_uid', 'stat'], 'integer'],
            [['related_table', 'note'], 'string', 'max' => 255],
            [['created_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_uid' => 'id']],
            [['updated_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_uid' => 'id']],
            [['checkout_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['checkout_uid' => 'id']],
            [['bed'], 'exist', 'skipOnError' => true, 'targetClass' => Bed::className(), 'targetAttribute' => ['bed' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_CHECKIN],
            [['stat'], 'in', 'range' => [self::STAT_CHECKIN, self::STAT_CHECKOUT, self::STAT_CHECKWAIT]],
            [['related_table'], 'default', 'value' => self::TABLE_STUDENT],
            [['related_table'], 'in', 'range' => [self::TABLE_STUDENT, self::TABLE_TEACHER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'related_table' => '关联表',
            'related_id' => '关联ID',
            'bed' => '床位',
            'note' => '备注',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'checkout_at' => '退宿时间',
            'created_uid' => '创建人',
            'updated_uid' => '修改人',
            'checkout_uid' => '退宿人',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_CHECKIN => "入住",
            self::STAT_CHECKOUT => "退宿",
            self::STAT_CHECKWAIT => "预定"
        ],
        'table' => [
            self::TABLE_TEACHER => "教职工",
            self::TABLE_STUDENT => "学生"
        ]
    ];

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedU() {
        return $this->hasOne(User::className(), ['id' => 'created_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedU() {
        return $this->hasOne(User::className(), ['id' => 'updated_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckoutU() {
        return $this->hasOne(User::className(), ['id' => 'checkout_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBed0() {
        return $this->hasOne(Bed::className(), ['id' => 'bed']);
    }

    public function getRoom() {
        return $this->hasOne(Room::className(), ['id' => 'rid'])->via('bed0');
    }

    public function getStudent() {
        return $this->hasOne(Student::className(), ['id' => 'related_id']);
    }

    public static function get_bed($sid = '') {
        $student = Student::find()->where(['id' => $sid])->one();
        $bid = static::find()->where(['related_table' => self::TABLE_STUDENT, 'stat' => [self::STAT_CHECKIN, self::STAT_CHECKWAIT]])->andFilterWhere(['not', ['related_id' => $sid]])->select(['bed'])->column();
        $forum = Forum::find()->where(['stat' => Forum::STAT_OPEN])->select(['id'])->column();
        $query = Room::find()->where(['stat' => Room::STAT_OPEN, 'fid' => $forum]);
        if ($student->gender) {
            $query->andWhere(['gender' => [NULL, $student->gender]]);
        }
        $room = $query->select(['id'])->column();
        $beds = Bed::find()->where(['stat' => Bed::STAT_OPEN])->andWhere(['not', ['id' => $bid]])->andFilterWhere(['rid' => $room])->all();
        $result = [];
        foreach ($beds as $bed) {
            $result[$bed['id']] = $bed->getAllName();
        }
        return $result;
    }

}
