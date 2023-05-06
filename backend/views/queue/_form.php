<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
use backend\models\Department;
use backend\models\Queue;
use backend\models\User;

//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
$command = Yii::$app->dbsdk;
$dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
if ($model->id != null) {
    $department = (new \yii\db\Query())
        ->select(['department.id_department', 'department.department_name', 'queue.next_queue_id'])
        ->from($dbn . '.queue')
        ->innerJoin($dbn . '.department', 'queue.id_department = department.id_department')
        ->where('queue.id = ' . $model->id)
        ->all();
    if ($department[0]['next_queue_id'] != null) {
        $next_queue = (new \yii\db\Query())
            ->select(['queue.disp_name'])
            ->from($dbn . '.queue')
            ->where('queue.id = ' . $department[0]['next_queue_id'])
            ->all();
        if ($next_queue == null || !isset($next_queue[0]) || !isset($next_queue[0]['disp_name'])) {
            $next_queue = null;
        }
    }
    else{
        $next_queue = null;
    }
    // echo"<pre>";var_dump($next_queue);die();
}
else{
    $department = null;
    $next_queue = null;
}
// echo"<pre>";var_dump($next_queue);die();
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">

            <div class="col-md-6">
                <?= $form->field($model, 'disp_name')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-6">
            <?= $form->field($model, 'next_queue_id')->widget(Select2::classname(), [
                'initValueText' => isset($next_queue) ? $department[0]['next_queue_id']." - ".$next_queue[0]['disp_name'] : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find the display name of the queue')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return '" . Yii::t('backend', 'Loading') . " ...'; }"),
                        'inputTooShort' => new JsExpression("function () { return '" . Yii::t('backend', 'Input at least 1 characters') . " ...'; }"),
                        'inputTooLong' => new JsExpression("function () { return '" . Yii::t('backend', 'Input maximum 255 characters') . "'; }"),
                        'noResults' => new JsExpression("function () { return '" . Yii::t('backend', 'No result found') . "'; }"),
                        'searching' => new JsExpression("function () { return '" . Yii::t('backend', 'Searching') . " ...'; }"),
                    ],
                    'ajax' => [
                        'url' => \yii\helpers\Url::toRoute(['queue/ajax-search-next-queue']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Next Queue')); ?>
        </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'waiting_time')->textInput(['maxlength' => 45]) ?>
            </div>
            <div class="col-md-6">
            <?= $form->field($model, 'id_department')->widget(Select2::classname(), [
                'initValueText' => isset($next_queue) ? $department[0]['id_department']." - ".$department[0]['department_name'] : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find department')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return '" . Yii::t('backend', 'Loading') . " ...'; }"),
                        'inputTooShort' => new JsExpression("function () { return '" . Yii::t('backend', 'Input at least 1 characters') . " ...'; }"),
                        'inputTooLong' => new JsExpression("function () { return '" . Yii::t('backend', 'Input maximum 255 characters') . "'; }"),
                        'noResults' => new JsExpression("function () { return '" . Yii::t('backend', 'No result found') . "'; }"),
                        'searching' => new JsExpression("function () { return '" . Yii::t('backend', 'Searching') . " ...'; }"),
                    ],
                    'ajax' => [
                        'url' => \yii\helpers\Url::toRoute(['queue/ajax-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Department')); ?>
        </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'id_mission')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Major::find()->all(), 'id_mission', 'mission_name'),
                    'size' => Select2::MEDIUM,
                    'options' => [
                        'placeholder' => Yii::t('backend', 'Choose a mission'),
                        'id_mission' => 'id_mission'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'addon' => [
                        'prepend' => [
                            'content' => '<i class="glyphicon glyphicon-search"></i>'
                        ]
                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'des')->textInput(['maxlength' => 45]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?php echo
                $form->field($model, 'type_queue')->dropDownList(
                    \common\helpers\Helpers::commonQueueStatusArr(),
                );;

                ?>

            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'start_time', [
                    'template' => '{label}{input}{error}{hint}',
                ])->widget(\kartik\widgets\TimePicker::classname(), [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'hh:ii',
                        'showMeridian' => false,
                        'defaultTime' => date('8:00'),
                    ],
                    'options' => [
                        'readonly' => true,
                    ]
                ]);
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $this->render('/common/_slim_image_field', [
                    'fieldName' => 'thumb',
                    'itemName' => 'thumbnail', // Vi du: adv, post, post-category de luu rieng tung folder
                    'fieldLabel' => Yii::t('backend', 'Thumbnail'),
                    'dataMinSize' => '100,100',
                    'dataSize' => '',
                    'dataForceSize' => '',
                    'dataRatio' => '100:100',
                    'model' => $model,
                    'dataWillRemove' => 'avatarWillChange',
                    'dataWillSave' => 'avatarWillChange',
                    'helpBlock' => Yii::t('backend', 'Ratio 100 x 100px(.jpg|png|jpeg)'),
                    'accept' => 'image/jpeg,image/jpg,image/png',
                ]) ?>

            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'end_time', [
                    'template' => '{label}{input}{error}{hint}',
                ])->widget(\kartik\widgets\TimePicker::classname(), [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'hh:ii',
                        'showMeridian' => false,
                        'defaultTime' => date('18:00'),
                    ],
                    'options' => [
                        'readonly' => true,
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="portlet-title">
        <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
            <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
        &nbsp;&nbsp;&nbsp;
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent green  btn-sm']) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>
<?php \common\components\slim\SlimAsset::register($this); ?>