<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Log;
use yii;

/**
 * LogSearch represents the model behind the search form of `backend\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * {@inheritdoc}
     */
    public $id_user;
    public function rules()
    {
        return [
            [['id', 'account', 'id_user'], 'integer'],
            [['action', 'name_object', 'id_object', 'created_at'], 'safe'],
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
        $query = Log::find()->joinWith('user')
        ->where('account = be_user.id');

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
            'log.id' => $this->id,
            'account' => $this->account,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['be_user.id' => $this->id_user])
            ->andFilterWhere(['like', 'name_object', $this->name_object])
            ->andFilterWhere(['like', 'id_object', $this->id_object]);
        if ($this->created_at != Yii::t('backend', 'All') && strpos($this->created_at, ' - ') > 0) {
            $request_times = \common\helpers\Helpers::splitDate($this->created_at, 'd/m/Y');
            $query->andFilterWhere(['BETWEEN', 'log.created_at', $request_times[0], $request_times[1]]);
        }
        return $dataProvider;
    }
}
