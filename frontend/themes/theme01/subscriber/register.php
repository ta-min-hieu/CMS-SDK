<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?= $this->render('@app/views/layouts/partials/_seo', [
    'title' => 'Đăng ký tài khoản ',
    'description' => '',
    'keywords' => '',
    'image' => 'http:///images/logo-retina.png',
    'url' =>  Yii::$app->request->getAbsoluteUrl(),
]); ?>
<?php

$this->registerJs(
    '$("document").ready(function(){
        $("#register-form").on("pjax:end", function() {
            window.location=window.location;
            // $.pjax.reload({container:"#countries"});  //Reload GridView
        });
    });'
);
?>

<div class="row">
    <div class="col-md-6 col-sm-6 box">
        <h3 class="box-title">Đăng ký tài khoản</h3>
        <p class="text" style="color: #40e0d0;">
            Vui lòng nhập thông tin để tạo tài khoản, hoặc click <a class="content-link" href="<?= \yii\helpers\Url::to(['subscriber/login']); ?>" title="Đăng nhập hệ thống">Đăng nhập</a> nếu bạn đã có tài khoản.
        </p>
        <br />
        <?php yii\widgets\Pjax::begin(['id' => 'register-form']) ?>
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>

        <?= $form->field($model, 'username')->textInput([
            'maxlength' => 200,
            'placeholder' => 'Tên đăng nhập',
        ])->label(false) ?>
        <?= $form->field($model, 'email')->textInput([
            'maxlength' => 200,
            'placeholder' => 'Email',
        ])->label(false) ?>
        <?= $form->field($model, 'password')->passwordInput([
            'maxlength' => 200,
            "autocomplete" => "off",
            'placeholder' => 'Mật khẩu',
        ])->label(false) ?>

        <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
            'template' => '<div class="col-md-6 col-sm-6 col-xs-6">{input}</div><div class="col-md-6 col-sm-6 col-xs-6">{image}<span style="color:#7fff00; cursor: pointer;" class="glyphicon glyphicon-refresh captcha-refresh-icon"></span></div>',
            'options' => [
                "class" => 'form-control row',
                "autocomplete" => "off",
                "placeholder" => 'Mã xác nhận',
                "style" => ""
            ],

        ])->label(false); ?>


        <div class="form-group">
            <?= Html::submitButton("Đăng Ký Thành Viên", ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?php yii\widgets\Pjax::end() ?>
    </div>
    <div class="col-md-6 col-sm-6 box">
        <h3 class="box-title">Đăng nhập nhanh</h3>
        <br />

        <?php $referUrl = ''; ?>
        <a class="fast-login" href="<?= \yii\helpers\Url::to(['subscriber/fast-login', 'authclient' => 'facebook']) ?>" title="Đăng nhập hệ thống bằng Facebook" >
            <img class="" src="/images/fblogin.png" />
        </a>
        <br />
        <a class="fast-login" href="<?= \yii\helpers\Url::to(['subscriber/fast-login', 'authclient' => 'google']) ?>" title="Đăng nhập hệ thống bằng Google" >
            <img class="" src="/images/gplus-login.png" />
        </a>
        <br />
        <a class="fast-login" href="<?= \yii\helpers\Url::to(['subscriber/fast-login', 'authclient' => 'twitter']) ?>" title="Đăng nhập hệ thống bằng Twitter" >
            <img class="" src="/images/twitter-login.png" /><br />
        </a>
        <br />


    </div>
</div>
