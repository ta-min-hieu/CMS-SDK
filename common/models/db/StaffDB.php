<?php

namespace common\models\db;
use backend\models\Customer;
use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 */
class StaffDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $sUserID;
    public static function tableName()
    {
        return 'staff';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsdk');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_name','phone_number','id_department','sUserID'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['phone_number'], 'string', 'max' => 20],
            [['hostname'], 'string', 'max' => 45],
            [['staff_name','position','id_department','status'], 'string', 'max' => 100],
            [['id_staff'], 'integer'],
            [['chinhanh','created_by','list'], 'safe'],
            [['phone_number'], 'unique'],
            ['phone_number','match','pattern'=>'/^(84|0)+([0-9]{8,10})$/',],
            ['phone_number', 'validateStartTime', 'enableClientValidation' => false],
        ];
    }
    public function validateStartTime()
    {
        if ($this->isNewRecord) {
            $customer = Customer::find()
                ->select('phonenumber')
                ->where(['phonenumber' => $this->phone_number])
                ->all();
            if ($customer != null)
                $this->addError('phone_number', 'Already have this phone number');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_staff' => Yii::t('backend', 'ID'),
            'username' => Yii::t('backend', 'Username'),
            'phone_number' => Yii::t('backend', 'Phone Number'),
            'staff_name' => Yii::t('backend', 'Staff Name'),
            'position' => Yii::t('backend', 'Position'),
            'id_department' => Yii::t('backend', 'Department Name'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'chinhanh' => Yii::t('backend', 'Chi nhánh'),
            'list' => Yii::t('backend', 'List'),
            'name_list' => Yii::t('backend', 'Name'),
            'don_vi' => Yii::t('backend', 'Đơn vị'),
            'created_by' => Yii::t('backend', 'Created By'),
            'search_string' => Yii::t('backend', 'Search'),
            'thumb' => Yii::t('backend', 'Thumbnail'),
            'sUserID' => Yii::t('backend', 'SUser ID'),
        ];
    }
}
