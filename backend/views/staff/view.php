<?php

use backend\models\Customer;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Department;
/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

?>
<div class="staff-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_staff',
            'username',
            'staff_name',
            'phone_number',
            'position',
            [
                'attribute' => 'id_department',
                'format' => 'raw',
                'value' => function ($object) {
                    $deparment=Department::findOne($object->id_department);
                    return ($deparment)?$deparment->department_name:null;
                }
            ],
            [
                'attribute' => 'Tỉnh/Thành phố',
                'format' => 'raw',
                'value' => function ($object) {
                    $deparment=Department::findOne($object->id_department);
                    return ($deparment)?Yii::t('backend',Yii::$app->params['province'][$deparment->id_province]):"Chưa có";
                }
            ],
            [
                'attribute' => 'state',
                'format' => 'raw',
                'value' => function ($object) {
                    $deparment=Customer::find($object->username)
                    ->where(['username' => $object->username])
                    ->one();
                    return ($deparment)?Yii::t('backend',Yii::$app->params['status2'][$deparment->state]):null;
                }
            ],
            'created_at',
        ],
    ]) ?>

</div>
