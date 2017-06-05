<?php

namespace dms\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dms\models\System;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $floor
 * @property integer $rid
 * @property string $name
 * @property string $note
 * @property string $fname
 * @property string $gender
 * @property integer $stat

 */
class Room extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%room}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fid', 'floor', 'rid', 'stat'], 'integer'],
            [['name', 'fid', 'floor'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'unique', 'targetAttribute' => ['fid', 'floor', 'rid', 'name'], 'message' => '{attribute}已经存在', 'on' => 'mul'],
            [['name'], 'unique', 'targetAttribute' => ['fid', 'floor', 'name'], 'message' => '{attribute}已经存在', 'on' => 'sig'],
            [['name'], 'string', 'max' => 30, 'message' => '{attribute}最长30字符'],
            [['note'], 'string', 'max' => 60, 'message' => '{attribute}最长60字符'],
            [['fid'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::className(), 'targetAttribute' => ['fid' => 'id']],
            [['floor'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::className(), 'targetAttribute' => ['floor' => 'id']],
            [['rid'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['rid' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_OPEN],
            [['stat'], 'in', 'range' => [self::STAT_OPEN, self::STAT_CLOSE]],
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
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
            'name' => '房间',
            'note' => '备注',
            'stat' => '状态',
            'rid' => '大室',
            'fname' => '标志',
            'gender' => '性别',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
        ],
        'gender' => [
            self::GENDER_MALE => "男",
            self::GENDER_FEMALE => "女"
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getGender() {
        return isset(self::$List['gender'][$this->gender]) ? self::$List['gender'][$this->gender] : null;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Room::className(), ['id' => 'rid'])->from(Room::tableName() . ' p');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren() {
        return $this->hasMany(Room::className(), ['rid' => 'id'])->from(Room::tableName() . ' c');
    }

    /**
     * @param $pid
     * @return array
     */
    public function getBroomList($fid, $floor, $id) {
        //大室不能变更小室
        $room = static::findOne(['rid' => $id]);
        if ($room !== null) {
            $broom = array();
        } else {
            $broom = Room::find()->where(['fid' => $fid, 'floor' => $floor, 'rid' => NULL])->andFilterWhere(['<>', 'id', $id])->orderBy(['name' => SORT_ASC])->all();
        }

        return ArrayHelper::map($broom, 'id', 'name');
    }

    /**
     * 通过楼苑id获得名称
     * @param $id 楼苑ID
     * @return array 楼苑ID-name
     */
    public static function get_forum_id($id = array()) {
        $query = Forum::find()->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->andFilterWhere(['id' => $id]);
        if (System::getValue('business_forum') === '1' && System::getValue('business_room') === '2') {
            $query->andWhere(['not', ['fup' => NULL]]);
        }
        $forum = $query->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    /**
     * 获得有房间的楼苑ID及名称
     * @return array 楼苑ID-name
     */
    public static function get_room_forum() {
        $fids = static::find()->select(['fid'])->distinct()->column();
        $forum = Forum::find()->where(['id' => $fids])->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    /**
     * 获得所有楼层ID及名称
     * @return array 楼层ID-name
     */
    public static function get_floor_id() {
        $floor = Parameter::find()->where(['name' => 'floor'])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

    /**
     * 获得有房间的楼层ID及名称
     * @param $fid 楼苑ID
     * @return array 楼层ID-name
     */
    public static function get_room_floor($fid = '') {
        $floors = static::find()->andFilterWhere(['fid' => $fid])->select(['floor'])->distinct()->column();
        $floor = Parameter::find()->where(['name' => 'floor', 'id' => $floors])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

    /**
     * 获得有大室ID及名称
     * @param $fid 楼苑ID
     * @param $floor 楼层ID
     * @return array 大室ID-name
     */
    public static function get_broom($fid = '', $floor = '') {
        $rooms = static::find()->where(['rid' => NULL])->andFilterWhere(['fid' => $fid, 'floor' => $floor])->select(['id', 'name', 'note', 'stat'])->orderBy(['name' => SORT_ASC])->indexBy('id')->asArray()->all();

        return $rooms;
    }

    /**
     * 获得有小室ID及名称
     * @param $bid 大室ID
     * @return array 大室ID-name
     */
    public static function get_sroom($bid = '') {
        $rooms = static::find()->andFilterWhere(['rid' => $bid])->select(['id', 'name', 'note', 'stat'])->orderBy(['name' => SORT_ASC])->indexBy('id')->asArray()->all();

        return $rooms;
    }

}
