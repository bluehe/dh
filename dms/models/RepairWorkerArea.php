<?php

namespace dms\models;

use Yii;

/**
 * This is the model class for table "{{%repair_worker_area}}".
 *
 * @property integer $worker
 * @property integer $area
 *
 * @property RepairWorker $worker0
 * @property Forum $area0
 */
class RepairWorkerArea extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%repair_worker_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['worker', 'area'], 'required'],
            [['worker', 'area'], 'integer'],
            [['worker'], 'exist', 'skipOnError' => true, 'targetClass' => RepairWorker::className(), 'targetAttribute' => ['worker' => 'id']],
            [['area'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::className(), 'targetAttribute' => ['area' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'worker' => 'Worker',
            'area' => 'Area',
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
    public function getAreaid() {
        return $this->hasOne(Forum::className(), ['id' => 'area']);
    }

}
