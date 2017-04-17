<?php

namespace dms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Bed;

/**
 * BedSearch represents the model behind the search form about `dms\models\Bed`.
 */
class BedSearch extends Bed {

    public $fid;
    public $floor;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'stat', 'fid', 'floor'], 'integer'],
            [['name', 'note', 'rid'], 'safe'],
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
        $query = Bed::find()->joinWith('room')->joinWith('forum')->joinWith('floor')->orderBy(['{{%forum}}.fsort' => SORT_ASC, '{{%forum}}.mark' => SORT_ASC, '{{%forum}}.fup' => SORT_ASC, '{{%forum}}.sort_order' => SORT_ASC, '{{%forum}}.id' => SORT_ASC, '{{%parameter}}.sort_order' => SORT_ASC, '{{%room}}.fname' => SORT_ASC, '{{%room}}.rid' => SORT_ASC, '{{%room}}.name' => SORT_ASC, 'name' => SORT_ASC]);
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort(false);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%bed}}.id' => $this->id,
            '{{%room}}.fid' => $this->fid,
            '{{%room}}.floor' => $this->floor,
            '{{%bed}}.stat' => $this->stat,
        ]);

        $rids = explode('-', $this->rid);
        if (isset($rids[1])) {
            $query->andFilterWhere(['like', '{{%room}}.fname', $rids[0]])
                    ->andWhere(['AND', ['like', '{{%room}}.name', $rids[1]], ['not', ['{{%room}}.rid' => NULL]]]);
        } else {
            $query->andFilterWhere(['OR',
                ['like', '{{%room}}.name', $this->rid],
                ['like', '{{%room}}.fname', $this->rid]
            ]);
        }

        $query->andFilterWhere(['like', '{{%bed}}.name', $this->name])
                ->andFilterWhere(['like', '{{%bed}}.note', $this->note]);

        return $dataProvider;
    }

}
