<?php

use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="modal-body">
    <?php if($model->isNewRecord) : ?>
    <?php Pjax::begin(['enablePushState' => false]); ?>
    <div>
        <h2 class="text-center mb-4">Tạo playlist</h2>
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true], 'enableClientValidation' => false]); ?>
        <!-- Text input -->
        <?= $form->field($model, 'playlist_name', ['options' => ['class' => 'input-group input-group-sm mb-3'], 'errorOptions' => ['tag' => 'span', 'class' => 'text-muted mt-2']])->textInput(['class' => 'form-control form-control-custom rounded-pill', 'style' => 'width: auto;', 'placeholder' => '', 'autocomplete' => 'off'])->label(false) ?>
        <div class="my-4">
            <div class="text-light d-flex justify-content-between align-items-center">
                <span class="fw-bold">Công khai</span>
                <div class="form-check form-switch form-switch-custom">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                        checked>
                    <label class="form-check-label text-muted fs-smx" for="flexSwitchCheckChecked">Bật</label>
                </div>

            </div>
            <p class="text-muted mt-2 fs-smx">Mọi người có thể nhìn thấy playlist này</p>
        </div>
        <div class="my-4">
            <div class="text-light d-flex justify-content-between align-items-center">
                <span class="fw-bold">Phát ngẫu nhiên</span>
                <div class="form-check form-switch form-switch-custom">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label text-muted  fs-smx" for="flexSwitchCheckDefault">Tắt</label>
                </div>
            </div>
            <p class="text-muted mt-2 fs-smx">Luôn phát ngẫu nhiên tất cả các bài hát</p>
        </div>
        <div><?= Html::submitButton(Yii::t('frontend', 'Đồng ý'), ['class' => 'btn btn-sm btn-gradient-custom rounded-pill d-block w-100']) ?></div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php Pjax::end(); ?>
    <?php else : ?>
    <p class="text-muted mt-2 fs-smx"><?= Yii::t('frontend', 'Tạo playlist thành công') ?></p>
    <?= Html::a(Yii::t('frontend', 'Đóng'), '#', ['class' => 'btn btn-sm btn-gradient-custom rounded-pill d-block w-100', 'data-dismiss' => 'modal']) ?>
    <?php endif ?>
</div>