<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form about `backend\models\VtUser`.
 */
class UserSearch extends User {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status','support_status','auto_close_sc','created_at', 'updated_at', 'branch_id', 'partner_id',], 'integer'],
            [['user_type', 'branch_id', 'partner_id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
            [['user_type', 'branch_id', 'partner_id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'trim'],
            [['user_type', 'branch_id', 'partner_id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'filter', 'filter' => 'trim'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['branch_id' => $this->branch_id])
            ->andFilterWhere(['partner_id' => $this->partner_id]);

        $user = Yii::$app->user->identity;
        switch ($user->user_type) {
            case 'admin':

                break;
            case 'ho':
                $query->andWhere(['!=', 'user_type', 'admin']);
                break;
            case 'branch':
                // Chi show cac user cua branch minh
                $query->andWhere(['!=', 'user_type', ['admin', 'ho']]);
                $query->andWhere([
                    'branch_id' => $user->branch_id,
//                    'partner_id' => ArrayHelper::map(Partner::getPartnersByBranch($user->branch_id), 'id', 'id')
                ]);
                break;
            case 'partner':

                break;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'support_status' => $this->support_status,
            'user_type' => $this->user_type,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

}
