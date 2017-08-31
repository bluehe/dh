<?php

namespace dh\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%recommend}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $img
 * @property integer $creared_at
 * @property integer $update_at
 * @property integer $status
 */
class Recommend extends \yii\db\ActiveRecord {

    const STATUS_WAIT = 1;
    const STATUS_OPEN = 2;
    const STATUS_CLOSE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%recommend}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'url', 'img', 'creared_at', 'update_at'], 'required', 'message' => '{attribute}不能为空'],
            [['creared_at', 'update_at', 'sort_order', 'stat'], 'integer'],
            [['name'], 'string', 'max' => 4, 'message' => '{attribute}最多4个字符'],
            [['url'], 'string', 'max' => 255],
            [['img'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '名称',
            'url' => '链接',
            'img' => '图片',
            'creared_at' => '创建时间',
            'update_at' => '更新时间',
            'sort_order' => '排序',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_WAIT => "等待",
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
        ]
    ];

    public function getStat() {

        $stat = isset(self::$List['stat'][$this->stat]) ? self::$List['stat'][$this->stat] : null;
        return $stat;
    }

    public static function get_recommend($stat = '') {
        $recommends = static::find()->andFilterWhere(['stat' => $stat])->select(['id', 'name', 'url', 'img', 'stat'])->orderBy(['sort_order' => SORT_DESC])->indexBy('id')->asArray()->all();

        return $recommends;
    }

}
