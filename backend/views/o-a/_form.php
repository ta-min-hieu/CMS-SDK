<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use backend\models\OfficialAccount;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */

$oa = OfficialAccount::findOne($model->id);
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
                <?= $form->field($model, 'id_official_account')->widget(Select2::classname(), [
                    'initValueText' => $oa ? $oa->id : '',
                    'options' => ['placeholder' => Yii::t('backend', 'Find Official Account')],
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
                            'url' => \yii\helpers\Url::toRoute(['o-a/ajax-search']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term }; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(result) { return result.text; }'),
                        'templateSelection' => new JsExpression('function (result) { return result.text; }')
                    ]
                ])->label(Yii::t('backend', 'Official Account')); ?>
            </div>
            <div class="col-md-6 excel">
                <?= $form->field($model, 'excel')->fileInput() ?>
                <?php if (!$model->isNewRecord && $model->excel) : ?>
                    <a>
                        <?= $model->getExcelPathUrl(); ?>
                        Your browser does not support the audio element.
                    </a>

                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'time', [
                    'template' => '{label}{input}{error}{hint}',
                ])->widget(\kartik\widgets\DateTimePicker::classname(), [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss'
                    ],
                    'options' => [
                        'readonly' => true,
                    ]
                ]);
                ?>
            </div>
                <div class="col-md-6">
                    <?php echo
                    $form->field($model, 'type')->dropDownList(
                        [
                            'text' => Yii::t('backend', 'Text'),
                            'image' => Yii::t('backend', 'Image'),
                            'video' => Yii::t('backend', 'Video'),
                        ],
                        ['prompt' => Yii::t('backend', '--Select Type--'),
                        'disabled' => !$model->isNewRecord ? true : false ],
                    );

                    ?>
                </div>
            <div class="col-md-6 text">
                <?= $form->field($model, 'text')->textInput(['maxlength' => 200]) ?>
            </div>
            <div class="col-md-6 image">
                <?= $this->render('/common/_slim_image_field', [
                    'fieldName' => 'image',
                    'itemName' => 'thumbnail', // Vi du: adv, post, post-category de luu rieng tung folder
                    'fieldLabel' => Yii::t('backend', 'Image'),
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
            <div class="col-md-6 video">
                <?php
                echo $form->field($model, 'video')->fileInput(['class' => 'video_here']); ?>
                <?php if (!$model->isNewRecord && $model->video) { ?>
                    <video controls>
                        <source src="<?= $model->getMediaPathUrl(); ?>" id="here">
                        Your browser does not support the audio element.
                    </video>

                <?php } else { ?>
                    <video controls>
                        <source src="" id="here">
                        Your browser does not support the audio element.
                    </video>
                <?php } ?>
            </div>
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
<script>
    window.onload = function() {
        $('.text').hide();
        $('.image').hide();
        $('.video').hide();
        if (document.getElementById("oa-type").value == 'prompt') {
            $('.text').hide();
            $('.image').hide();
            $('.video').hide();
        }
        if (document.getElementById("oa-type").value == 'text') {
            $('.text').show();
            $('.image').hide();
            $('.video').hide();
        }
        if (document.getElementById("oa-type").value == 'image') {
            $('.image').show();
            $('.text').hide();
            $('.video').hide();
        }
        if (document.getElementById("oa-type").value == 'video') {
            $('.video').show();
            $('.text').hide();
            $('.image').hide();
        }
        $("#oa-type").change(function() {
            if (document.getElementById("oa-type").value == 'prompt') {
                $('.text').hide();
                $('.image').hide();
                $('.video').hide();
            }
            if (document.getElementById("oa-type").value == 'text') {
                $('.text').show();
                $('.image').hide();
                $('.video').hide();
            }
            if (document.getElementById("oa-type").value == 'image') {
                $('.image').show();
                $('.text').hide();
                $('.video').hide();
            }
            if (document.getElementById("oa-type").value == 'video') {
                $('.video').show();
                $('.text').hide();
                $('.image').hide();
            }
        })

        $(document).on("change", ".video_here", function(evt) {
            var $source = $('#here');
            console.log($source);
            $source[0].src = URL.createObjectURL(this.files[0]);
            $source.parent()[0].load();
        });
    }
</script>