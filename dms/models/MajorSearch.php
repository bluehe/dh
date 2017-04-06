<?php

namespace dms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Major;

/**
 * MajorSearch represents the model behind the search form about `dms\models\Major`.
 */
class MajorSearch extends Major {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'sort_order', 'college'], 'integer'],
            [['name'], 'safe'],
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
        $query = Major::find();
        $query->joinWith(['colleges']);
        //$query->select("{{%major}}.*, {{%college}}.name as cname");
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                    'college' => SORT_ASC,
                    'sort_order' => SORT_ASC,
                ]],
        ]);


        $sort = $dataProvider->getSort();
        $sort->attributes['college'] = [
            'asc' => ['{{%college}}.sort_order' => SORT_ASC],
            'desc' => ['{{%college}}.sort_order' => SORT_DESC],
        ];
        $dataProvider->setSort($sort);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%major}}.id' => $this->id,
            '{{%major}}.sort_order' => $this->sort_order,
            '{{%major}}.college' => $this->college,
        ]);

        $query->andFilterWhere(['like', '{{%major}}.name', $this->name]);

        return $dataProvider;
    }

}
