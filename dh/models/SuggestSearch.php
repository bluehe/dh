<?php

namespace dh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dh\models\Suggest;

/**
 * SuggestSearch represents the model behind the search form about `dh\models\Suggest`.
 */
class SuggestSearch extends Suggest {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'created_at', 'updated_at', 'reply_uid', 'stat'], 'integer'],
            [['content', 'reply_content'], 'safe'],
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
        $query = Suggest::find();

        // add conditions that should always apply here

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
            'reply_uid' => $this->reply_uid,
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'reply_content', $this->reply_content]);

        if ($this->created_at) {
            $range = explode('至', $this->created_at);
            $start = strtotime($range[0]);
            $end = strtotime($range[1]) + 86399;
            $query->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end]);
        }

        return $dataProvider;
    }

}
