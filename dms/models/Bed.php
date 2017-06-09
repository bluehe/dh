<?php

namespace dms\models;

use \yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dms\models\System;

/**
 * This is the model class for table "{{%bed}}".
 *
 * @property integer $id
 * @property integer $rid
 * @property string $name
 * @property string $note
 * @property integer $stat
 *
 * @property Room $r
 */
class Bed extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

    public $fid;
    public $flid;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%bed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rid', 'stat'], 'integer'],
            [['name', 'rid'], 'required', 'message' => '{attribute}不能为空'],
            [['name'], 'string', 'max' => 30, 'message' => '{attribute}最长30字符'],
            [['name'], 'unique', 'targetAttribute' => ['rid', 'name'], 'message' => '{attribute}已经存在'],
            [['note'], 'string', 'max' => 60, 'message' => '{attribute}最长60字符'],
            [['rid'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['rid' => 'id']],
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
            'rid' => '房间',
            'name' => '床位',
            'note' => '备注',
            'stat' => '状态',
            'fid' => '楼苑',
            'flid' => '楼层'
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
        ]
    ];

    public function getStat() {

        $stat = self::$List['stat'][$this->stat];
        return isset($stat) ? $stat : null;
    }

    public function getAllName() {
        $forum = $this->forum->fup ? $this->forum->parent->name . $this->forum->name : $this->forum->name;
        $floor = $this->floor->v;
        $room = $this->room->fname ? $this->room->fname . '-' . $this->room->name : $this->room->name;
        return $forum . $floor . $room . '-' . $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom() {
        return $this->hasOne(Room::className(), ['id' => 'rid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForum() {
        return $this->hasOne(Forum::className(), ['id' => 'fid'])->via('room');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloor() {
        return $this->hasOne(Parameter::className(), ['id' => 'floor'])->via('room');
    }

    /**
     * @param $pid
     * @return array
     */
    public function getRoomList($fid, $floor) {

        $query = Room::find()->where(['fid' => $fid, 'floor' => $floor])->orderBy(['fname' => SORT_ASC, 'rid' => SORT_ASC, 'name' => SORT_ASC]);
        if (System::getValue('business_roomtype') === '1') {
            if (System::getValue('business_bed') === '2') {
                $query->andWhere(['not', ['rid' => NULL]]);
            } else {
                $bids = Room::find()->where(['fid' => $fid, 'floor' => $floor])->andWhere(['not', ['rid' => NULL]])->select(['rid'])->distinct()->column();
                $query->andWhere(['not', ['id' => $bids]]);
            }
        } else {
            $query->andWhere(['rid' => NULL]);
        }
        $rooms = $query->all();
        $result = [];
        foreach ($rooms as $room) {
            $result[$room['id']] = $room['rid'] ? $room['fname'] . '-' . $room['name'] : $room['name'];
        }
        return $result;
    }

    //得到楼苑ID-name 键值数组
    public static function get_bed_forum() {
        $rids = static::find()->select(['rid'])->distinct()->column();
        $fids = Room::find()->select(['fid'])->where(['id' => $rids])->distinct()->column();
        $forum = Forum::find()->where(['id' => $fids])->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    //得到楼层ID-name 键值数组
    public static function get_bed_floor() {
        $rids = static::find()->select(['rid'])->distinct()->column();
        $floors = Room::find()->select(['floor'])->where(['id' => $rids])->distinct()->column();
        $floor = Parameter::find()->where(['name' => 'floor', 'id' => $floors])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

    public static function get_room_bed($id = '') {
        $bed = static::find()->where(['rid' => $id])->orderBy(['ABS(name)' => SORT_ASC])->all();
        return $bed;
    }

}
