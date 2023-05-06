<?php

use awesome\backend\widgets\AwsBaseHtml;
use awesome\backend\form\AwsActiveForm;
use backend\models\Menu;
use kartik\select2\Select2;
use mdm\admin\AutocompleteAsset;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $title string */
/* @var $form AwsActiveForm */

$menuIcons = Yii::$app->params['menu-icon'];
$dataIcon = [];
foreach ($menuIcons as $icon) {
    $dataIcon[$icon] = $icon;
}

?>

<?php $form = AwsActiveForm::begin(); ?>

    <div class="portlet light portlet-fit portlet-form bordered menu-form">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-paper-plane "></i>
                <span class="caption-subject sbold uppercase">
                <?= $title ?>
                </span>
            </div>

        </div>
        <div class="portlet-body">
            <div class="form-body">
                <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

                <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name']) ?>

                <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>

                <?= $form->field($model, 'order')->input('number') ?>

                <div class="form-group">
                    <label class="control-label" for="icon">Icon</label>
                    <?php
                    $format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text; // optgroup
    return '<i class="' + state.id + '"></i> ' + state.text;
}
SCRIPT;
                    $escape = new JsExpression("function(m) { return m; }");
                    $this->registerJs($format, View::POS_HEAD);
                    ?>
                    <?=
                    Select2::widget([
                        'name' => 'Menu[icon]',
                        'data' => $dataIcon,
                        'value' => $model->icon,
                        'options' => ['placeholder' => Yii::t('backend', 'Choose icon menu ...')],
                        'pluginOptions' => [
                            'templateResult' => new JsExpression('format'),
                            'templateSelection' => new JsExpression('format'),
                            'escapeMarkup' => $escape,
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

            </div>
        </div>
        <div class="portlet-title">
            <div class="actions">
                <?= AwsBaseHtml::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-info   btn-sm']) ?>
                <button type="button" name="back" class="btn btn-transparent black  btn-sm"
                        onclick="history.back(-1);">
                    <i class="fa fa-angle-left"></i> Back
                </button>
            </div>
        </div>

    </div>

<?php AwsActiveForm::end(); ?>

<?php
AutocompleteAsset::register($this);

$options1 = Json::htmlEncode([
    'source' => Menu::find()->select(['name'])->column()
]);
$this->registerJs("$('#parent_name').autocomplete($options1);");

$options2 = Json::htmlEncode([
    'source' => Menu::getSavedRoutes()
]);
$this->registerJs("$('#route').autocomplete($options2);");