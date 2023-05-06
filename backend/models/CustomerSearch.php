<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `backend\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sUserID', 'serviceID', 'username', 'phonenumber', 'country', 'birthday', 'avatar', 'fullname', 'pushid', 'app_provision', 'app_revision', 'device_os_type', 'device_os_version', 'device_id', 'created_at', 'app_id', 'type_user', 'state'], 'safe'],
            [['gender'], 'integer'],
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
        if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $query = Customer::find();
        }
        else{
            $query = Customer::find()
            ->where('id_province = '.User::findOne(\Yii::$app->user->id)->id_province);
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
            'gender' => $this->gender,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'sUserID', $this->sUserID])
            ->andFilterWhere(['like', 'serviceID', $this->serviceID])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'phonenumber', $this->phonenumber])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'pushid', $this->pushid])
            ->andFilterWhere(['like', 'app_provision', $this->app_provision])
            ->andFilterWhere(['like', 'app_revision', $this->app_revision])
            ->andFilterWhere(['like', 'device_os_type', $this->device_os_type])
            ->andFilterWhere(['like', 'device_os_version', $this->device_os_version])
            ->andFilterWhere(['like', 'device_id', $this->device_id])
            ->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'type_user', $this->type_user])
            ->andFilterWhere(['like', 'state', $this->state]);

        return $dataProvider;
    }
}
