
<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
if ($isFinish): ?>
	<div class="message"><?= $message; ?></div>
	<br />
	<div class="modal-footer" style="padding-bottom: 0;">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('frontend', 'Đóng'); ?></button>
	</div>
<?php else: ?>
	<?php 
	$form = ActiveForm::begin(['id' => 'get-otp-form', 'options' => [
		'class' => 'ajax-modal-form', 
		'action' => Url::to(['subscriber/get-otp']),
	]]);
	?>
	<?= $form->field($getOtpModal, 'msisdn', [
		'options' => [
			'class' => 'form-group row', 
		], 
		'template' => '<div class="col-12" >{input}{error}</div>',
	])->textInput([


		"class" => "form-control",
		"autocomplete" => "off",
		"placeholder" => Yii::t('frontend', 'Số điện thoại')
	])->label(false); ?>

	<?= $form->field($getOtpModal, 'captcha', [
		'options' => [
			'class' => 'form-group field-loginform-captcha row', 
		], 

	])->widget(\yii\captcha\Captcha::className(), [
		'template' => '<div class="col-6 ">{input}</div><div class="col-6">{image}<span id="otp-refresh-captcha" class="fa fa-refresh captcha-refresh-icon" aria-hidden="true"></span></div><div class="clearfix"></div>',
		'imageOptions' => [
	        'class' => 'captcha-img'
	    ], 
		'options' => [
			"class" => 'form-control',
			"autocomplete" => "off",
			"placeholder" => Yii::t('frontend', 'Mã xác nhận'),
			"style" => ""
		],

	])->label(false); ?>
	<div class="clearfix"></div>
	<div class="modal-footer" style="padding-bottom: 0;">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('frontend', 'Đóng'); ?></button>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
		<button type="submit" class="btn btn-lucky"><?= Yii::t('frontend', 'Lấy OTP'); ?></button>
	</div>
	<?php ActiveForm::end(); ?>
	<?php endif;?>