<?php

use backend\models\Department;
use backend\models\User;
use yii\bootstrap\Html;
use \yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Edit Working Time');
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
            <label for="id_province" class="control-label">Province: </label>
            <?php if(User::findOne(\Yii::$app->user->id)->id_province == null){?>
                <select id="id_province" class="form-control" name="id_province" aria-required="true" aria-invalid="false">
                    <option value="10">10 - Hà Nội</option>
                    <option value="16">16 - Hưng Yên</option>
                    <option value="30">30 - Tuyên Quang</option>
                    <option value="18">18 - Hải Phòng</option>
                    <option value="28">28 - Vĩnh Phúc</option>
                    <option value="52">52 - Quảng Trị</option>
                    <option value="42">42 - Nam Định</option>
                    <option value="44">44 - Thanh Hóa</option>
                    <option value="82">82 - Bình Dương</option>
                    <option value="43">43 - Ninh Bình</option>
                    <option value="53">53 - Huế</option>
                    <option value="65">65 - Khánh Hòa</option>
                    <option value="97">97 - Cà Mau</option>
                    <option value="90">90 - Cần Thơ</option>
                    <option value="09">09 - TMĐT Hà Nội</option>
                    <option value="51">51 - Quảng Bình</option>
                    <option value="38">38 - Điện Biên</option>
                    <option value="39">39 - Lai Châu</option>
                    <option value="46">46 - Nghệ An</option>
                    <option value="63">63 - Đắk Lắk</option>
                    <option value="56">56 - Quảng Nam</option>
                    <option value="60">60 - Gia Lai</option>
                    <option value="22">22 - Bắc Ninh</option>
                    <option value="80">80 - Bình Thuận</option>
                    <option value="81">81 - Đồng Nai</option>
                    <option value="86">86 - Tiền Giang</option>
                    <option value="31">31 - Hà Giang</option>
                    <option value="84">84 - Tây Ninh</option>
                    <option value="25">25 - Thái Nguyên</option>
                    <option value="35">35 - Hòa Bình</option>
                    <option value="41">41 - Thái Bình</option>
                    <option value="83">83 - Bình Phước</option>
                    <option value="89">89 - Vĩnh Long</option>
                    <option value="87">87 - Đồng Tháp</option>
                    <option value="95">95 - Sóc Trăng</option>
                    <option value="70">70 - Hồ Chí Minh</option>
                    <option value="96">96 - Bạc Liêu</option>
                    <option value="92">92 - Kiên Giang</option>
                    <option value="85">85 - Long An</option>
                    <option value="93">93 - Bến Tre</option>
                    <option value="29">29 - Phú Thọ</option>
                    <option value="79">79 - Bà Rịa Vũng Tàu</option>
                    <option value="17">17 - Hải Dương</option>
                    <option value="94">94 - Trà Vinh</option>
                    <option value="26">26 - Bắc Kạn</option>
                    <option value="91">91 - Hậu Giang</option>
                    <option value="88">88 - An Giang</option>
                    <option value="55">55 - Đà Nẵng</option>
                    <option value="59">59 - Bình Định</option>
                    <option value="23">23 - Bắc Giang</option>
                    <option value="36">36 - Sơn La</option>
                    <option value="33">33 - Lào Cai</option>
                    <option value="57">57 - Quảng Ngãi</option>
                    <option value="27">27 - Cao Bằng</option>
                    <option value="40">40 - Hà Nam</option>
                    <option value="20">20 - Quảng Ninh</option>
                    <option value="64">64 - Đắk Nông</option>
                    <option value="32">32 - Yên Bái</option>
                    <option value="66">66 - Ninh Thuận</option>
                    <option value="48">48 - Hà Tĩnh</option>
                    <option value="67">67 - Lâm Đồng</option>
                    <option value="58">58 - Kon Tum</option>
                    <option value="24">24 - Lạng Sơn</option>
                    <option value="62">62 - Phú Yên</option>
                </select>
                <?php } else{ ?>
                    <select id="id_province" class="form-control" name="id_province" aria-required="true" aria-invalid="false">
                        <option value="<?=User::findOne(\Yii::$app->user->id)->id_province ?>"><?=User::findOne(\Yii::$app->user->id)->id_province." - ".Department::findOne(User::findOne(\Yii::$app->user->id)->id_province)->province ?></option>    
                    </select>
            <?php }?>
            </div>
            <div class="col-md-6">
                <label for="type_queue" class="control-label">Type Queue: </label>
                <select id="type_queue" class="form-control" name="type_queue" aria-required="true" aria-invalid="false">
                    <option value="Bưu Cục">Bưu Cục</option>
                    <option value="CSKH">CSKH</option>
                </select>
                </br></br>
            </div>
            <div class="col-md-6">
            <label class="control-label">Work Start Time: </label>
            </div>
            <div class="col-md-6">
            <label class="control-label">Work End Time: </label>
            </div>
            <div class="col-md-3">
                <label class="control-label">Hour: </label>
                <select id="start_time_hour" class="form-control" name="start_time_hour" aria-required="true" aria-invalid="false">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label">Minutes: </label>
                <select id="start_time_minute" class="form-control" name="start_time_minute" aria-required="true" aria-invalid="false">
                    <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="55">55</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label">Hour: </label>
                <select id="end_time_hour" class="form-control" name="end_time_hour" aria-required="true" aria-invalid="false">
                <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label">Minutes: </label>
                <select id="end_time_minute" class="form-control" name="end_time_minute" aria-required="true" aria-invalid="false">
                <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="55">55</option>
                </select>
</br></br>
            </div>
            <div class="portlet-title col-md-12">
                <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
                &nbsp;&nbsp;&nbsp;
                <?= Html::submitButton(Yii::t('backend', 'OK'), ['class' => 'btn btn-transparent green  btn-sm']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php \common\components\slim\SlimAsset::register($this); ?>
<script src="/js/timepicker.min.js"></script>
<link href="/css/timepicker.min.css" rel="stylesheet"/>
<!-- <script>
    window.onload = function() {
        var start_time = new TimePicker('start_time', {
            lang: 'en',
            theme: 'dark'
        });
        start_time.on('change', function(evt) {
            var value = (evt.hour || '00') + ':' + (evt.minute || '00');
            evt.element.value = value;
        });
        var end_time = new TimePicker('end_time', {
            lang: 'en',
            theme: 'dark'
        });
        end_time.on('change', function(evt) {
            var value = (evt.hour || '00') + ':' + (evt.minute || '00');
            evt.element.value = value;
        });
    }
</script> -->