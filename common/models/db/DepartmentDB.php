<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 */
class DepartmentDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
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
            [['department_name','address','management_id','village','district','id_province','id_department'], 'required'],
            [['address','village'], 'string', 'max' => 200],
            [['district','province','department_name','management_id','id_department'], 'string', 'max' => 100],
            [['id_department'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_department' => Yii::t('backend', 'ID'),
            'department_name' => Yii::t('backend', 'Department Name'),
            'address' => Yii::t('backend', 'Address'),
            'village' => Yii::t('backend', 'Village'),
            'district' => Yii::t('backend', 'District'),
            'id_province' => Yii::t('backend', 'Province'),
            'management_id' => Yii::t('backend', 'Management Id'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

}
