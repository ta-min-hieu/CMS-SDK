<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii;

class ThreadChatSearch extends ThreadChat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thread_id', 'timestamp', 'status', 'queue_name', 'agent', 'ojid', 'default_queue', 'accepted_at', 'session_id', 'ended_at'], 'safe'],
            [['created_at'], 'trim'],
            [['created_at'], 'filter', 'filter' => 'trim'],
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
        $query = ThreadChat::find();

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
        ]);

        $query->andFilterWhere(['like', 'thread_id', $this->thread_id])
            ->andFilterWhere(['like', 'timestamp', $this->timestamp])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'queue_name', $this->queue_name])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'ojid', $this->ojid])
            ->andFilterWhere(['like', 'default_queue', $this->default_queue])
            ->andFilterWhere(['like', 'accepted_at', $this->accepted_at])
            ->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'ended_at', $this->ended_at])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
        return $dataProvider;
    }
}
