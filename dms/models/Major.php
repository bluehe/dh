<?php

namespace dms\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%major}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort_order
 * @property integer $college
 *
 * @property College $college0
 */
class Major extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%major}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'sort_order', 'college'], 'required', 'message' => '{attribute}不能为空'],
            [['sort_order', 'college'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['college'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['college' => 'id']],
            [['name', 'college'], 'unique', 'targetAttribute' => ['name', 'college']],
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
            'college' => '学院',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColleges() {
        return $this->hasOne(College::className(), ['id' => 'college']);
    }

    //得到学院ID-name 键值数组
    public static function get_college_id() {
        $college = College::find()->orderBy('sort_order')->all();
        return ArrayHelper::map($college, 'id', 'name');
    }

}
