<?php

namespace dh\models;

use Yii;

/**
 * This is the model class for table "{{%suggest}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $reply_uid
 * @property string $reply_content
 * @property integer $stat
 *
 * @property User $u
 * @property User $replyU
 */
class Suggest extends \yii\db\ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = -1;
    const STAT_REPLY = 2;

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
            [['uid', 'reply_uid', 'stat'], 'integer'],
            [['content', 'reply_content'], 'string'],
            [['uid', 'content'], 'required'],
            [['reply_content'], 'required', 'on' => 'reply'],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['reply_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reply_uid' => 'id']],
            ['stat', 'default', 'value' => self::STAT_OPEN],
            ['stat', 'in', 'range' => [self::STAT_OPEN, self::STAT_CLOSE, self::STAT_REPLY]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户',
            'content' => '内容',
            'created_at' => '提交时间',
            'updated_at' => '回复时间',
            'reply_uid' => '回复人',
            'reply_content' => '回复',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "已提交",
            self::STAT_CLOSE => "取消",
            self::STAT_REPLY => "已回复"
        ]
    ];

    public function getStat() {
        $stat = isset(self::$List['stat'][$this->stat]) ? self::$List['stat'][$this->stat] : null;
        return $stat;
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

}
