<?php

namespace dms\models;

use Yii;

/**
 * This is the model class for table "{{%repair_worker_type}}".
 *
 * @property integer $worker
 * @property integer $type
 *
 * @property RepairWorker $worker0
 * @property Parameter $type0
 */
class RepairWorkerType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%repair_worker_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['worker', 'type'], 'required'],
            [['worker', 'type'], 'integer'],
            [['worker'], 'exist', 'skipOnError' => true, 'targetClass' => RepairWorker::className(), 'targetAttribute' => ['worker' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => Parameter::className(), 'targetAttribute' => ['type' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'worker' => 'Worker',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkerid() {
        return $this->hasOne(RepairWorker::className(), ['id' => 'worker']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeid() {
        return $this->hasOne(Parameter::className(), ['id' => 'type']);
    }

}
