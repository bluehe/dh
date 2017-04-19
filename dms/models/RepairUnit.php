<?php

namespace dms\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%repair_unit}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $company
 * @property string $tel
 * @property string $email
 * @property string $address
 * @property string $note
 * @property integer $stat
 */
class RepairUnit extends ActiveRecord {

    const STAT_OPEN = 1;
    const STAT_CLOSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%repair_unit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'company'], 'required', 'message' => '{attribute}不能为空'],
            [['stat'], 'integer'],
            ['email', 'email', 'message' => '{attribute}格式错误'],
            [['name'], 'string', 'max' => 8, 'message' => '{attribute}最长8个字符'],
            [['tel', 'email', 'company'], 'string', 'max' => 64, 'message' => '{attribute}最长64个字符'],
            [['address', 'note'], 'string', 'max' => 255],
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
            'name' => '简称',
            'company' => '公司名',
            'tel' => '电话',
            'email' => '电子邮件',
            'address' => '地址',
            'note' => '备注',
            'stat' => '状态',
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
