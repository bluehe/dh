<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Pickup;

/**
 * PickupSearch represents the model behind the search form about `dms\models\Pickup`.
 */
class PickupSearch extends Pickup {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'created_at', 'end_at', 'stat'], 'integer'],
            [['type', 'name', 'tel', 'goods', 'address', 'content'], 'safe'],
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
    public function search($params, $pageSize = '') {
        $query = Pickup::find();

        // add conditions that should always apply here

        if ($pageSize > 0) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $pageSize,
                ],
                'sort' => ['defaultOrder' => [
                        'id' => SORT_DESC,
                    ]],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => [
                        'id' => SORT_DESC,
                    ]],
            ]);
        }

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
            'type' => $this->type,
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'tel', $this->tel])
                ->andFilterWhere(['like', 'goods', $this->goods])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'content', $this->content]);
        if ($this->created_at) {
            $createdAt = strtotime($this->created_at);
            $createdAtEnd = $createdAt + 24 * 3600;
            $query->andWhere(['>=', 'created_at', $createdAt])->andWhere(['<=', 'created_at', $createdAtEnd]);
        }

        return $dataProvider;
    }

}
