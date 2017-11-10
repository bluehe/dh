<?php

namespace dh\models;

use Yii;

/**
 * This is the model class for table "{{%user_sign}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $y
 * @property string $m
 * @property string $d
 * @property integer $series
 * @property string $note
 * @property integer $created_at
 *
 * @property User $u
 */
class UserSign extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_sign}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'y', 'm', 'd', 'created_at'], 'required'],
            [['uid', 'series', 'created_at'], 'integer'],
            [['y'], 'string', 'max' => 4],
            [['m', 'd'], 'string', 'max' => 2],
            [['note'], 'string', 'max' => 255],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户',
            'y' => '年',
            'm' => '月',
            'd' => '日',
            'series' => '连续天数',
            'note' => '备注',
            'created_at' => '签到时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    //是否关注
    public static function exist_sign($uid) {
        $time = strtotime(date('Y-m-d', time()));
        return static::find()->where(['and', ['>', 'created_at', $time], ['uid' => $uid]])->exists();
    }

}
