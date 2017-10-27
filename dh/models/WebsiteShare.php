<?php

namespace dh\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "{{%website_share}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $wid
 * @property integer $cid
 * @property string $cname
 * @property string $title
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $check_uid
 * @property string $note
 * @property integer $stat
 *
 * @property User $u
 * @property Website $w
 * @property Category $c
 * @property User $checkU
 */
class WebsiteShare extends \yii\db\ActiveRecord {

    const STAT_WAIT = 1;
    const STAT_OPEN = 2;
    const STAT_CLOSE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%website_share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'wid', 'cid', 'created_at', 'updated_at', 'check_uid', 'stat'], 'integer'],
            [['title', 'url'], 'required'],
            [['cname'], 'required', 'when' => function ($model) {
                    return empty($model->cid);
                },
                'whenClient' => "function(){return $('#websiteshare-cid').val()=='';}"
            ],
            [['cname'], 'string', 'max' => 8],
            [['title', 'url', 'note'], 'string', 'max' => 255],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['wid'], 'exist', 'skipOnError' => true, 'targetClass' => Website::className(), 'targetAttribute' => ['wid' => 'id']],
            [['cid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cid' => 'id']],
            [['check_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['check_uid' => 'id']],
            [['stat'], 'default', 'value' => self::STAT_WAIT],
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
            'wid' => '网址',
            'cid' => '分类',
            'cname' => '分类名称',
            'title' => '名称',
            'url' => '网址',
            'created_at' => '提交时间',
            'updated_at' => '审核时间',
            'check_uid' => '审核员',
            'note' => '备注',
            'stat' => '状态',
        ];
    }

    public static $List = [
        'stat' => [
            self::STAT_WAIT => "待审核",
            self::STAT_OPEN => "通过",
            self::STAT_CLOSE => "拒绝"
        ]
    ];

    public function getStat() {
        $stat = isset(self::$List['stat'][$this->stat]) ? self::$List['stat'][$this->stat] : null;
        return $stat;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU() {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getW() {
        return $this->hasOne(Website::className(), ['id' => 'wid']);
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
    public function getCheckU() {
        return $this->hasOne(User::className(), ['id' => 'check_uid']);
    }

}
