<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Suggest;

/**
 * SuggestSearch represents the model behind the search form about `dms\models\Suggest`.
 */
class SuggestSearch extends Suggest {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'created_at', 'reply_at', 'reply_uid', 'evaluate1', 'evaluate', 'stat'], 'integer'],
            [['type', 'serial', 'name', 'tel', 'title', 'content', 'reply_content', 'note'], 'safe'],
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
        $query = Suggest::find();

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
            'reply_uid' => $this->reply_uid,
            'evaluate1' => $this->evaluate1,
            'evaluate' => $this->evaluate,
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'serial', $this->serial])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'tel', $this->tel])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'reply_content', $this->reply_content])
                ->andFilterWhere(['like', 'note', $this->note]);

        if ($this->created_at) {
            $createdAt = strtotime($this->created_at);
            $createdAtEnd = $createdAt + 24 * 3600;
            $query->andWhere(['>=', 'created_at', $createdAt])->andWhere(['<=', 'created_at', $createdAtEnd]);
        }

        return $dataProvider;
    }

}
