<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Major;

/**
 * MajorSearch represents the model behind the search form of `backend\models\Major`.
 */
class MajorSearch extends Major
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mission'], 'integer'],
            [['mission_name', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Major::find();

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
            'id_mission' => $this->id_mission,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'mission_name', $this->mission_name]);

        return $dataProvider;
    }
}
