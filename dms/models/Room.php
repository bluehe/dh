<?php

namespace dms\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
 * @property integer $stat

 */
class Room extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

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
                //[['rid'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['rid' => 'id']],
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
            'rid' => '大室',
            'fname' => '标志',
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

    //得到楼苑ID-name 键值数组
    public static function get_forum_id() {
        $forum = Forum::find()->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    //得到楼苑ID-name 键值数组
    public static function get_room_forum() {
        $fids = static::find()->select(['fid'])->distinct()->asArray()->all();
        $forum_id = array();
        foreach ($fids as $fid) {
            $forum_id[] = $fid['fid'];
        }
        $forum = Forum::find()->where(['id' => $forum_id])->orderBy(['fsort' => SORT_ASC, 'mark' => SORT_ASC, 'fup' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
        return ArrayHelper::map($forum, 'id', 'name');
    }

    //得到楼苑ID-name 键值数组
    public static function get_floor_id() {
        $floor = Parameter::find()->where(['name' => 'floor'])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

    //得到楼苑ID-name 键值数组
    public static function get_room_floor() {
        $fids = static::find()->select(['floor'])->distinct()->asArray()->all();
        $floor_id = array();
        foreach ($fids as $fid) {
            $floor_id[] = $fid['floor'];
        }
        $floor = Parameter::find()->where(['name' => 'floor', 'id' => $floor_id])->orderBy(['sort_order' => SORT_ASC])->all();
        return ArrayHelper::map($floor, 'id', 'v');
    }

}
