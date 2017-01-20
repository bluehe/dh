<?php

namespace dms\models;

/**
 * This is the model class for table "{{%college}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort_order
 */
class College extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%college}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'sort_order'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique', 'message' => '{attribute}已经存在'],
            [['sort_order'], 'integer'],
            ['sort_order', 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '名称',
            'sort_order' => '排序',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMajors() {
        return $this->hasMany(Major::className(), ['college' => 'id']);
    }

}
