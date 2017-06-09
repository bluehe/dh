<?php

namespace dms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dms\models\Student;

/**
 * StudentSearch represents the model behind the search form about `dms\models\Student`.
 */
class StudentSearch extends Student {

    public $bed;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'uid', 'college', 'major', 'grade', 'teacher', 'stat'], 'integer'],
            [['name', 'stuno', 'gender', 'tel', 'email', 'address', 'note', 'bed'], 'safe'],
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
        $query = Student::find()->joinWith('college0')->joinWith('major0')->joinWith('grade0')->joinWith('teacher0')->orderBy(['{{%student}}.id' => SORT_DESC]);

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
            '{{%student}}.gender' => $this->gender,
            '{{%student}}.college' => $this->college,
            '{{%student}}.major' => $this->major,
            '{{%student}}.grade' => $this->grade,
            '{{%student}}.teacher' => $this->teacher,
            '{{%student}}.stat' => $this->stat,
        ]);

        $query->andFilterWhere(['like', '{{%student}}.name', $this->name])
                ->andFilterWhere(['like', '{{%student}}.stuno', $this->stuno])
                ->andFilterWhere(['like', '{{%student}}.tel', $this->tel])
                ->andFilterWhere(['like', '{{%student}}.email', $this->email])
                ->andFilterWhere(['like', '{{%student}}.address', $this->address])
                ->andFilterWhere(['like', '{{%student}}.note', $this->note]);

        return $dataProvider;
    }

}
