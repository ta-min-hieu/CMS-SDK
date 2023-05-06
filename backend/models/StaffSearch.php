<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Staff;

/**
 * StaffSearch represents the model behind the search form of `backend\models\Staff`.
 */
class StaffSearch extends Staff
{
    public $search_string;
    public $sUserID;
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
            [['id_staff'], 'integer'],
            [['username', 'staff_name', 'phone_number', 'position', 'id_department', 'status', 'created_at', 'updated_at','search_string','sUserID'], 'safe'],
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
        // if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $query = Staff::find()->joinWith('customer')
            ->where('type_user = 2');
        // }
        // else{
        //     $query = Staff::find()->joinWith('customer')
        //     ->where('type_user = 2')
        //     ->andWhere('id_province = '.User::findOne(\Yii::$app->user->id)->id_province);
        // }

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
            'id_staff' => $this->id_staff,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id_department' => $this->id_department,
        ]);

        $query->andFilterWhere(['like', 'staff.username', $this->username])
            ->andFilterWhere(['like', 'staff_name', $this->staff_name])
            ->andFilterWhere(['sUserID' => $this->sUserID])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere ( [ 'OR' ,
            [ 'like' , 'phone_number' , $this->search_string ],
            [ 'like' , 'staff_name' , $this->search_string ],
        ] )
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
