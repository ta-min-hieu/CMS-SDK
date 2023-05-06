<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BoxChat;

/**
 * BoxChatSearch represents the model behind the search form of `backend\models\BoxChat`.
 */
class BoxChatSearch extends BoxChat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_box_chat', 'type_box_chat'], 'integer'],
            [['description', 'title_question', 'greeting', 'created_at', 'updated_at'], 'safe'],
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
        $query = BoxChat::find();

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
            'id_box_chat' => $this->id_box_chat,
            'type_box_chat' => $this->type_box_chat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'title_question', $this->title_question])
            ->andFilterWhere(['like', 'greeting', $this->greeting]);

        return $dataProvider;
    }
}
