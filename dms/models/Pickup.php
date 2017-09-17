<?php

namespace dms\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pickup}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $uid
 * @property string $name
 * @property string $tel
 * @property string $goods
 * @property string $address
 * @property string $content
 * @property integer $created_at
 * @property integer $end_at
 * @property integer $stat
 *
 * @property User $u
 */
class Pickup extends \yii\db\ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_SUCCESS = 2;
    const STAT_FAIL = 3;
    const STAT_CLOSE = -1;
    const TYPE_PICK = 1;
    const TYPE_LOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%pickup}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'name', 'tel', 'goods', 'stat'], 'required', 'message' => '{attribute}不能为空'],
            [['uid', 'end_uid', 'created_at', 'end_at', 'stat'], 'integer'],
            [['type', 'goods', 'address'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 16, 'message' => '{attribute}最长16个字符'],
            [['tel'], 'string', 'max' => 64, 'message' => '{attribute}最长64个字符'],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['end_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['end_uid' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
            [['stat'], 'in', 'range' => [self::STAT_OPEN, self::STAT_SUCCESS, self::STAT_FAIL, self::STAT_CLOSE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => '类型',
            'uid' => '用户',
            'name' => '联系人',
            'tel' => '联系电话',
            'goods' => '物品',
            'address' => '地址',
            'content' => '内容',
            'created_at' => '发布时间',
            'end_at' => '结束时间',
            'end_uid' => '结束人',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "进行中",
            self::STAT_SUCCESS => "成功",
            self::STAT_FAIL => "失败",
            self::STAT_CLOSE => "关闭",
        ],
        'type' => [
            self::TYPE_PICK => '招领',
            self::TYPE_LOSE => '寻物',
        ],
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getType() {

        $type = self::$List['type'][$this->type];
        return isset($type) ? $type : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    public function getE() {
        return $this->hasOne(User::className(), ['id' => 'end_uid']);
    }

    public static function get_type_total($a = '', $start = '', $end = '') {

        $query = static::find()->andFilterWhere(['stat' => $a])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end]);
        return $query->groupBy(['type'])->select(['count(*)'])->indexBy('type')->column();
    }

}
