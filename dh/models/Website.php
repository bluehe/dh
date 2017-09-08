<?php

namespace dh\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%website}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $cid
 * @property string $title
 * @property string $url
 * @property integer $sort_order
 * @property integer $click_num
 * @property integer $is_open
 * @property integer $share_status
 * @property integer $share_cid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $stat
 * *
 * @property Category $c
 * @property User $u
 *  @property Category $shareC
 */
class Website extends \yii\db\ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;
    const ISOPEN_OPEN = 1;
    const ISOPEN_CLOSE = 2;
    const SHARE_DEFAULT = 0;
    const SHARE_WAIT = 1;
    const SHARE_ACTIVE = 2;
    const SHARE_CLOSE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%website}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'cid', 'title', 'url', 'sort_order', 'click_num', 'created_at', 'updated_at'], 'required'],
            [['uid', 'cid', 'sort_order', 'click_num', 'is_open', 'created_at', 'updated_at', 'stat'], 'integer'],
            [['title', 'url'], 'string', 'max' => 255],
            [['cid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cid' => 'id']],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['share_cid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['share_cid' => 'id']],
            [['share_status'], 'default', 'value' => self::SHARE_DEFAULT],
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
            'uid' => '用户',
            'cid' => '分类',
            'title' => '名称',
            'url' => '网址',
            'sort_order' => '排序',
            'click_num' => '点击数',
            'share_stat' => '分享状态',
            'share_cid' => '分享分类',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_open' => '是否公开',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_OPEN => "启用",
            self::STAT_CLOSE => "关闭"
        ],
        'is_open' => [
            self::ISOPEN_OPEN => "开放",
            self::ISOPEN_CLOSE => "关闭"
        ],
        'share_status' => [
            self::SHARE_DEFAULT => "未分享",
            self::SHARE_WAIT => "待审核",
            self::SHARE_ACTIVE => "分享中",
            self::SHARE_CLOSE => "未通过"
        ]
    ];

    public function getStat() {

        $stat = isset(self::$List['stat'][$this->stat]) ? self::$List['stat'][$this->stat] : null;
        return $stat;
    }

    public function getIsOpen() {

        $is_open = isset(self::$List['is_open'][$this->is_open]) ? self::$List['is_open'][$this->is_open] : null;
        return $is_open;
    }

    public function getShareStatus() {

        $result = isset(self::$List['share_status'][$this->share_status]) ? self::$List['share_status'][$this->share_status] : null;
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC() {
        return $this->hasOne(Category::className(), ['id' => 'cid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    public function getShareC() {
        return $this->hasOne(Category::className(), ['id' => 'share_cid']);
    }

    public static function get_website($limit = '', $cid = '', $stat = self::STAT_OPEN, $is_open = '') {
        $query = static::find()->andFilterWhere(['cid' => $cid, 'stat' => $stat, 'is_open' => $is_open])->orderBy(['sort_order' => SORT_DESC]);
        if ($limit) {
            $query->limit($limit);
        }
        $websites = $query->indexBy('id')->asArray()->all();
        return $websites;
    }

}
