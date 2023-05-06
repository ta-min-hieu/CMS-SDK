<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserLoginFailed;

/**
 * UserLoginFailedSearch represents the model behind the search form about `backend\models\UserLoginFailed`.
 */
class UserLoginFailedSearch extends UserLoginFailed
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at'], 'safe'],
            [['username', 'ip'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = UserLoginFailed::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        if(strpos($this->created_at, ' - ') > 0) {
            $request_times = \common\helpers\Helpers::splitDate($this->created_at, 'd/m/Y');

            $query->andFilterWhere(['between', 'created_at', strtotime($request_times[0]), strtotime($request_times[1])]);
        } else {
            $query->andFilterWhere(['created_at' => $this->created_at]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
