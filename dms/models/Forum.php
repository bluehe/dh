<?php

namespace dms\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%forum}}".
 *
 * @property integer $id
 * @property integer $fup
 * @property string $name
 * @property integer $sort_order
 * @property integer $mold
 * @property integer $stat
 *
 */
class Forum extends ActiveRecord {

    const MOLD_SIG = 1;
    const MOLD_MUL = 2;
    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%forum}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fup', 'sort_order', 'mold', 'stat', 'mark'], 'integer'],
            [['name', 'sort_order', 'mold', 'stat'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 30, 'message' => '{attribute}最多30个字符'],
            [['fup'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::className(), 'targetAttribute' => ['fup' => 'id']],
            [['name'], 'unique', 'targetAttribute' => ['name'], 'message' => '{attribute}已经存在'],
            [['mold'], 'default', 'value' => self::MOLD_SIG],
            [['mold'], 'in', 'range' => [self::MOLD_SIG, self::MOLD_MUL]],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
            [['stat'], 'in', 'range' => [self::STAT_OPEN, self::STAT_CLOSE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'fup' => '上级楼苑',
            'name' => '名称',
            'sort_order' => '排序',
            'mold' => '房型',
            'stat' => '状态',
            'mark' => '标志',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFups() {
        return $this->hasOne(Forum::className(), ['id' => 'fup'])->from(Forum::tableName() . ' fup');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums() {
        return $this->hasMany(Forum::className(), ['fup' => 'id'])->from(Forum::tableName() . ' sub');
    }

    //得到顶级楼苑ID-name 键值数组
    public static function get_forumfup_id() {
        $forums = Forum::find()->where(['fup' => NULL])->orderBy(['sort_order'])->all();
        return ArrayHelper::map($forums, 'id', 'name');
    }

    public static $List = [
        'mold' => [
            self::MOLD_SIG => "单间",
            self::MOLD_MUL => "套间"
        ],
        'stat' => [
            self::STAT_OPEN => "开放",
            self::STAT_CLOSE => "关闭"
        ]
    ];

    public function getMold() {
        $mold = self::$List['mold'][$this->mold];
        return isset($mold) ? $mold : null;
    }

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

}
