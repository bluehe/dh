<?php

namespace dh\models;

use Yii;

//use common\models\User;

/**
 * This is the model class for table "{{%website_click}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $website
 * @property string $ip
 * @property integer $created_at
 *
 * @property User $u
 * @property Website $website0
 */
class WebsiteClick extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%website_click}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'website', 'created_at'], 'integer'],
            [['ip', 'created_at'], 'required'],
            [['ip'], 'string', 'max' => 16],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['website'], 'exist', 'skipOnError' => true, 'targetClass' => Website::className(), 'targetAttribute' => ['website' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uid' => '用户',
            'website' => '网址',
            'ip' => 'IP',
            'created_at' => '时间',
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
    public function getW() {
        return $this->hasOne(Website::className(), ['id' => 'website']);
    }

}
