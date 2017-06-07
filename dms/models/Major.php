<?php

namespace dms\models;

use yii\db\ActiveRecord;
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
class Major extends ActiveRecord {

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
            [['name'], 'string', 'max' => 30, 'message' => '{attribute}最多30个字符'],
            [['college'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['college' => 'id']],
            [['name'], 'unique', 'targetAttribute' => ['name', 'college'], 'message' => '{attribute}已经存在'],
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

    //得到学院ID-name 键值数组
    public static function get_major_college() {
        $cids = static::find()->select(['college'])->distinct()->asArray()->all();
        $college_id = array();
        foreach ($cids as $cid) {
            $college_id[] = $cid['college'];
        }
        $college = College::find()->where(['id' => $college_id])->orderBy('sort_order')->all();
        return ArrayHelper::map($college, 'id', 'name');
    }

    //得到专业ID-name 键值数组
    public static function get_college_major($id) {

        $majors = static::find()->where(['college' => $id])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($majors, 'id', 'name');
    }

}
