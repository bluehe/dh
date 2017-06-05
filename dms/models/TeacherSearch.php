<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Teacher;

/**
 * TeacherSearch represents the model behind the search form about `dms\models\Teacher`.
 */
class TeacherSearch extends Teacher {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'college', 'stat'], 'integer'],
            [['name', 'gender', 'tel', 'email', 'address', 'note'], 'safe'],
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
        $query = Teacher::find();

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
            'uid' => $this->uid,
            'college' => $this->college,
            'gender' => $this->gender,
            'stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'tel', $this->tel])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

}
