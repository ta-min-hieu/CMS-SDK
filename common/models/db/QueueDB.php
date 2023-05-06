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
class QueueDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue';
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
            [['waiting_time','type_queue','disp_name','id_department', 'id_mission', 'thumb'], 'required'],
            [['queue_name','hostname','waiting_time','des','type_queue','start_time','end_time'], 'string', 'max' => 45],
            [['thumb','disp_name'], 'string', 'max' => 255],
            [['id_department'], 'string', 'max' => 100],
            [['id', 'next_queue_id', 'id_mission'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'queue_name' => Yii::t('backend', 'Queue Name'),
            'hostname' => Yii::t('backend', 'Host Name'),
            'thumb' => Yii::t('backend', 'Thumbnail'),
            'disp_name' => Yii::t('backend', 'Display Name'),
            'next_queue_id' => Yii::t('backend', 'Next Queue Id'),
            'waiting_time' => Yii::t('backend', 'Waiting Time (Minutes)'),
            'id_department' => Yii::t('backend', 'Mã Phòng Ban'),
            'des' => Yii::t('backend', 'Description'),
            'id_mission' => Yii::t('backend', 'Mission'),
            'type_queue' => Yii::t('backend', 'Type queue'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'status' => Yii::t('backend', 'Status'),
            'department_name' => Yii::t('backend', 'Department Name'),
            'start_time' => Yii::t('backend', 'Work Start Time'),
            'end_time' => Yii::t('backend', 'Work End Time'),
            'phonenumber' => Yii::t('backend', 'Staff Phonenumber'),
        ];
    }
}
