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
            [['name', 'k', 'v'], 'required', 'message' => '不能为空'],
            [['v'], 'string'],
            [['name', 'k'], 'string', 'max' => 32],
            [['name', 'k'], 'unique', 'targetAttribute' => ['name', 'k']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '类型',
            'k' => '代码',
            'v' => '数值',
        ];
    }

}
