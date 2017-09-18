<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\CheckOrder;

/**
 * CheckOrderSearch represents the model behind the search form about `dms\models\CheckOrder`.
 */
class CheckOrderSearch extends CheckOrder {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'related_id', 'bed', 'created_at', 'updated_at', 'checkout_at', 'created_uid', 'updated_uid', 'checkout_uid', 'stat'], 'integer'],
            [['related_table', 'note'], 'safe'],
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
        $query = CheckOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'related_id' => $this->related_id,
            'bed' => $this->bed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'checkout_at' => $this->checkout_at,
            'created_uid' => $this->created_uid,
            'updated_uid' => $this->updated_uid,
            'checkout_uid' => $this->checkout_uid,
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'related_table', $this->related_table])
                ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

}
