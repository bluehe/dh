<?php

namespace dms\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "{{%suggest}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $serial
 * @property integer $uid
 * @property string $name
 * @property string $tel
 * @property string $title
 * @property string $content
 * @property integer $created_at
 * @property integer $reply_at
 * @property integer $reply_uid
 * @property string $reply_content
 * @property integer $evaluate
 * @property string $note
 * @property integer $stat
 *
 * @property User $u
 * @property User $replyU
 */
class Suggest extends \yii\db\ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = -1;
    const STAT_REPLY = 2;
    const STAT_EVALUATE = 3;
    const TYPE_COM = 1;
    const TYPE_ADV = 2;
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
        return '{{%suggest}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'serial', 'uid', 'created_at', 'content'], 'required'],
            [['uid', 'created_at', 'end_at', 'reply_at', 'reply_uid', 'evaluate1', 'evaluate', 'stat'], 'integer'],
            [['type', 'title', 'content', 'reply_content', 'note'], 'string', 'max' => 255],
            [['serial', 'name'], 'string', 'max' => 16],
            [['tel'], 'string', 'max' => 64],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['reply_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reply_uid' => 'id']],
            [['type'], 'default', 'value' => self::TYPE_COM],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => '类型',
            'serial' => '编号',
            'uid' => '上报人',
            'name' => '姓名',
            'tel' => '电话',
            'title' => '标题',
            'content' => '内容',
            'created_at' => '创建时间',
            'reply_at' => '回复时间',
            'reply_uid' => '回复人',
            'reply_content' => '回复内容',
            'evaluate' => '评价',
            'note' => '备注',
            'end_at' => '结束时间',
            'stat' => '状态',
        ];
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
    public function getReplyU() {
        return $this->hasOne(User::className(), ['id' => 'reply_uid']);
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "已提交",
            self::STAT_CLOSE => "取消",
            self::STAT_REPLY => "已回复",
            self::STAT_EVALUATE => "已评价",
        ],
        'type' => [
            self::TYPE_COM => "投诉",
            self::TYPE_ADV => "建议",
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

    public function getType() {

        $type = self::$List['type'][$this->type];
        return isset($type) ? $type : null;
    }

    public function getEvaluate1() {

        $evaluate = self::$List['evaluate'][$this->evaluate1];
        return isset($evaluate) ? $evaluate : null;
    }

}
