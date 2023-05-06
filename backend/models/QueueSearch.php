<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Queue;
use Yii;

/**
 * QueueSearch represents the model behind the search form of `backend\models\Queue`.
 */
class QueueSearch extends Queue
{
    public $id_province, $phonenumber;
    public function formName()
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'next_queue_id', 'phonenumber'], 'integer'],
            [['queue_name', 'hostname', 'thumb', 'disp_name', 'waiting_time', 'id_department', 'des', 'created_at', 'updated_at','type_queue','id_mission','id_province',], 'safe'],
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
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $query = Queue::find()->joinWith('department')
            // ->leftJoin($dbn . '.queue_agent', 'queue_agent.queue_name = queue.queue_name')
            // ->leftJoin($dbn . '.staff', 'staff.username = queue_agent.agent_name')
            ->where('queue.id_department = department.id_department');
            // ->distinct('queue.queue_name');
        }
        else{
            $query = Queue::find()->joinWith('department')
            // ->leftJoin($dbn . '.queue_agent', 'queue_agent.queue_name = queue.queue_name')
            // ->leftJoin($dbn . '.staff', 'staff.username = queue_agent.agent_name')
            ->where('id_province = '.User::findOne(\Yii::$app->user->id)->id_province);
            // ->distinct('queue.queue_name');
        }

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
            'queue.id' => $this->id,
            'queue.next_queue_id' => $this->next_queue_id,
            'queue.created_at' => $this->created_at,
            'queue.updated_at' => $this->updated_at,
            'queue.type_queue' => $this->type_queue,
            'queue.id_mission' => $this->id_mission,
            'department.id_province' => $this->id_province,
            'queue.id_department' => $this->id_department,
            // 'staff.phone_number' => $this->phonenumber,
        ]);

        $query->andFilterWhere(['like', 'queue.queue_name', $this->queue_name])
            ->andFilterWhere(['like', 'queue.hostname', $this->hostname])
            ->andFilterWhere(['like', 'queue.thumb', $this->thumb])
            ->andFilterWhere(['like', 'queue.disp_name', $this->disp_name])
            ->andFilterWhere(['like', 'queue.waiting_time', $this->waiting_time])
            ->andFilterWhere(['like', 'queue.des', $this->des]);
        return $dataProvider;
    }
}
