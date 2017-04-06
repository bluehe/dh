<?php

namespace dms\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%broom}}".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $floor
 * @property string $name
 * @property string $note
 * @property integer $stat
 *
 */
class Broom extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%broom}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fid', 'floor', 'stat'], 'integer'],
            [['name', 'fid', 'floor'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 30, 'message' => '{attribute}最长30字符'],
            [['note'], 'string', 'max' => 60, 'message' => '{attribute}最长60字符'],
            [['fid'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::className(), 'targetAttribute' => ['fid' => 'id']],
            [['floor'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::className(), 'targetAttribute' => ['floor' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'fid' => '楼苑',
            'floor' => '楼层',
            'name' => '名称',
            'note' => '备注',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "开放",
            self::STAT_CLOSE => "关闭"
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums() {
        return $this->hasOne(Forum::className(), ['id' => 'fid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloors() {
        return $this->hasOne(Parameter::className(), ['id' => 'floor']);
    }

    //得到楼苑ID-name 键值数组
    public static function get_forum_id() {
        $forum = Forum::find()->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    //得到楼苑ID-name 键值数组
    public static function get_floor_id() {
        $floor = Parameter::find()->where(['name' => 'floor'])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

}
