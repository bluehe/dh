<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\RepairOrder;

/**
 * RepairOrderSearch represents the model behind the search form about `dms\models\RepairOrder`.
 */
class RepairOrderSearch extends RepairOrder {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'repair_type', 'repair_area', 'evaluate', 'accept_at', 'accept_uid', 'repair_at', 'repair_uid', 'worker_id', 'end_at', 'stat'], 'integer'],
            [['serial', 'address', 'title', 'content', 'created_at', 'note'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = RepairOrder::find()->joinWith('type')->joinWith('area')->joinWith('worker');

        // add conditions that should always apply here


        if (!Yii::$app->user->can('报修管理') && Yii::$app->user->can('维修管理')) {
            $worker = RepairWorker::find()->select(['id'])->where(['uid' => Yii::$app->user->identity->id])->distinct()->column();
            $query->andWhere(['worker_id' => $worker]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'repair_type' => $this->repair_type,
            'repair_area' => $this->repair_area,
            'evaluate' => $this->evaluate,
            'accept_at' => $this->accept_at,
            'accept_uid' => $this->accept_uid,
            'repair_at' => $this->repair_at,
            'repair_uid' => $this->repair_uid,
            'worker_id' => $this->worker_id,
            'end_at' => $this->end_at,
            '{{%repair_order}}.stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'serial', $this->serial])
                ->andFilterWhere(['like', 'address', $this->address])
//                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'note', $this->note]);
        if ($this->created_at) {
            $createdAt = strtotime($this->created_at);
            $createdAtEnd = $createdAt + 24 * 3600;
            $query->andWhere(['>=', 'created_at', $createdAt])->andWhere(['<=', 'created_at', $createdAtEnd]);
        }

        return $dataProvider;
    }

}
