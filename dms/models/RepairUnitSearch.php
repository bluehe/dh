<?php

namespace dms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\RepairUnit;

/**
 * RepairUnitSearch represents the model behind the search form about `dms\models\RepairUnit`.
 */
class RepairUnitSearch extends RepairUnit {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'stat'], 'integer'],
            [['name', 'company', 'tel', 'email', 'address', 'note'], 'safe'],
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
        $query = RepairUnit::find();

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
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'company', $this->company])
                ->andFilterWhere(['like', 'tel', $this->tel])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

}
