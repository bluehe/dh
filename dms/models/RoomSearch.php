<?php

namespace dms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Room;

/**
 * RoomSearch represents the model behind the search form about `dms\models\Room`.
 */
class RoomSearch extends Room {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'fid', 'floor', 'rid', 'stat'], 'integer'],
            [['name', 'note', 'fname'], 'safe'],
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
        $query = Room::find();
        $query->joinWith(['forums', 'floors'])->orderBy(['{{%forum}}.fsort' => SORT_ASC, '{{%forum}}.mark' => SORT_ASC, '{{%forum}}.fup' => SORT_ASC, '{{%forum}}.sort_order' => SORT_ASC, '{{%forum}}.id' => SORT_ASC, '{{%parameter}}.sort_order' => SORT_ASC, 'fname' => SORT_ASC, 'rid' => SORT_ASC, 'name' => SORT_ASC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => ['defaultOrder' => [
//                    'fid' => SORT_ASC,
//                    'floor' => SORT_ASC,
//                    'name' => SORT_ASC,
//                ]],
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
            '{{%room}}.id' => $this->id,
            '{{%room}}.fid' => $this->fid,
            '{{%room}}.floor' => $this->floor,
            '{{%room}}.stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', '{{%room}}.name', $this->name])
                ->andFilterWhere(['like', '{{%room}}.note', $this->note]);

        return $dataProvider;
    }

}
