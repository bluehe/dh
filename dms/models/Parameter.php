<?php

namespace dms\models;

use Yii;

/**
 * This is the model class for table "{{%parameter}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $k
 * @property string $v
 */
class Parameter extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%parameter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'sort_order', 'v'], 'required', 'message' => '{attribute}不能为空'],
            [['v'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['v'], 'unique', 'targetAttribute' => ['name', 'v'], 'message' => '{attribute}已经存在'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '类型',
            'sort_order' => '排序',
            'v' => '数值',
        ];
    }

}
