<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;

use \frontend\models\SystemSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\Contact */
/* @var $title string */
/* @var $form ActiveForm */
?>

<?php if ($model !== null): ?>
    <?php $errors = $model->getErrors(); ?>
<form id="ajax-form" action="<?= \yii\helpers\Url::to(['contact/ajax-form', 'pd_id' => $model->product_id]); ?>" method="post">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
            <div class="form-body row">
                <div class="col-md-6">
                    <div class="form-group field-contact-fullname required <?= (isset($errors['fullname']))? 'has-error':'' ?>">
                        <label class="control-label" for="contact-fullname">Họ và tên</label>
                        <input type="text" id="contact-fullname" class="form-control" name="Contact[fullname]" value="<?= $model->fullname;?>" maxlength="255" aria-required="true">
                        <div class="help-block"><?= $errors['fullname'][0]; ?></div>
                    </div>
                    <div class="form-group field-contact-phonenumber required <?= (isset($errors['phonenumber']))? 'has-error':'' ?>">
                        <label class="control-label" for="contact-phonenumber">Số điện thoại</label>
                        <input type="text" id="contact-phonenumber" class="form-control" name="Contact[phonenumber]" value="<?= $model->phonenumber;?>" maxlength="20" aria-required="true">
                        <div class="help-block"><?= $errors['phonenumber'][0]; ?></div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group field-contact-email <?= (isset($errors['email']))? 'has-error':'' ?>">
                        <label class="control-label" for="contact-email">Email</label>
                        <input type="text" id="contact-email" class="form-control" name="Contact[email]" value="<?= $model->email;?>" maxlength="255">
                        <div class="help-block"><?= $errors['email'][0]; ?></div>
                    </div>
                    <div class="form-group field-contact-address <?= (isset($errors['address']))? 'has-error':'' ?>">
                        <label class="control-label" for="contact-address">Địa chỉ</label>
                        <input type="text" id="contact-address" class="form-control" name="Contact[address]" value="<?= $model->address;?>" maxlength="255">
                        <div class="help-block"><?= $errors[''][0]; ?></div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group field-contact-body required <?= (isset($errors['body']))? 'has-error':'' ?>">
                        <label class="control-label" for="contact-body">Nội dung</label>
                        <div class="help-block"><?= $errors['body'][0]; ?></div>
                        <textarea id="contact-body" class="form-control" name="Contact[body]" rows="4" aria-required="true"><?= $model->body;?></textarea>
                    </div>
                    <div class="form-group">

                        <div class="form-group field-contact-recaptcha required <?= (isset($errors['reCaptcha']))? 'has-error':'' ?>">

                            <input type="text" id="contact-recaptcha" class="form-control form-control-solid placeholder-no-fix" name="Contact[reCaptcha]" autocomplete="off" placeholder="Mã xác nhận" style="float:left; width: 130px;" aria-required="true">
                            <img id="contact-recaptcha-image" src="/site/captcha?v=<?= time(); ?>" alt=""> <a onclick="javascript:$('#contact-recaptcha-image').click()" href="#refresh-captcha" class="glyphicon glyphicon-refresh refresh-captcha"></a>

                            <div class="help-block"><?= $errors['reCaptcha'][0]; ?></div>
                        </div>
                    </div>
                </div>

            </div>

        <div class="portlet-title">

            <?= Html::submitButton(Yii::t('frontend', 'Gửi liên hệ'), ['class' => 'btn btn-info']) ?>
            &nbsp;&nbsp;
            <input type="reset" class="btn btn-warning" value="Xóa thông tin"/>

            <?php if ($usePopup): ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="btn-close">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?= Yii::t('frontend', 'Đóng'); ?></button>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
        <br/>
        <b>Xin trân trọng cảm ơn! </b><br>

    </form>

<?php else: ?>
    <div style="font-size: 16px; margin: 15px 0;">
        <?= Yii::t('frontend', 'Cảm ơn quý khách đã liên hệ! Chúng tôi sẽ phản hồi lại sớm nhất có thể!'); ?>
        <br />
        <br />
        <?php if ($usePopup): ?>
        <center>
            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><?= Yii::t('frontend', 'Đóng'); ?></button>
        </center>
        <?php endif; ?>
    </div>
<?php endif; ?>
