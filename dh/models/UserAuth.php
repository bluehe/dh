<?php

namespace dh\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_auth}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $type
 * @property string $open_id
 * @property integer $created_at
 *
 */
class UserAuth extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'open_id', 'type'], 'required'],
            [['uid'], 'integer'],
            [['type'], 'string', 'max' => 10],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户ID',
            'type' => '类型',
            'open_id' => '第三方ID',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    public static function getTouser($uid) {
        return static::find()->select(['open_id'])->where(['uid' => $uid, 'type' => 'weixin'])->column();
    }

}
