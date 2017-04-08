<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Broom;

/**
 * BroomSearch represents the model behind the search form about `dms\models\Broom`.
 */
class BroomSearch extends Broom {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'fid', 'floor', 'stat'], 'integer'],
            [['name', 'note'], 'safe'],
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
        $query = Broom::find();
        $query->joinWith(['forums', 'floors']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                    'fid' => SORT_ASC,
                    'floor' => SORT_ASC,
                    'name' => SORT_ASC,
                ]],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['fid'] = [
            'asc' => ['{{%forum}}.fsort' => SORT_ASC, '{{%forum}}.mark' => SORT_ASC, '{{%forum}}.fup' => SORT_ASC, '{{%forum}}.sort_order' => SORT_ASC, '{{%forum}}.sort_order' => SORT_ASC],
            'desc' => ['{{%forum}}.fsort' => SORT_DESC, '{{%forum}}.mark' => SORT_DESC, '{{%forum}}.fup' => SORT_ASC, '{{%forum}}.sort_order' => SORT_DESC, '{{%forum}}.id' => SORT_DESC],
        ];
        $sort->attributes['floor'] = [
            'asc' => ['{{%parameter}}.sort_order' => SORT_ASC],
            'desc' => ['{{%parameter}}.sort_order' => SORT_DESC],
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
            '{{%broom}}.id' => $this->id,
            '{{%broom}}.fid' => $this->fid,
            '{{%broom}}.floor' => $this->floor,
            '{{%broom}}.stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', '{{%broom}}.name', $this->name])
                ->andFilterWhere(['like', '{{%broom}}.note', $this->note]);

        return $dataProvider;
    }

}
