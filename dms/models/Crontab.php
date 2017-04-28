<?php

namespace dms\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%crontab}}".
 *
 * @property integer $id
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $interval_time
 * @property string $content
 * @property integer $exc_at
 * @property integer $stat
 */
class Crontab extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%crontab}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_at', 'content'], 'required'],
            [['start_at', 'end_at', 'interval_time', 'exc_at', 'stat'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'interval_time' => 'Interval Time',
            'content' => 'Content',
            'exc_at' => 'Exc At',
            'stat' => 'Stat',
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

}
