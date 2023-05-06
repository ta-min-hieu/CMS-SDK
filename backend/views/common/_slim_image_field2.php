<?php
$dataMinSize = isset($dataMinSize)? $dataMinSize: '480,270';
$dataSize = isset($dataSize)? $dataSize: '480,270';
$dataForceSize = isset($dataForceSize)? $dataForceSize: '480,270';
$accept = isset($accept)? $accept: 'image/jpeg,image/jpg,image/png,image/gif';
$dataRatio = isset($dataRatio)? $dataRatio: '16:9';
$dataWillSave = isset($dataWillSave)? $dataWillSave: 'dataWillChange';
$dataWillRemove = isset($dataWillRemove)? $dataWillRemove: 'dataWillChange';

// MB
$dataMaxFileSize = isset($dataMaxFileSize)? $dataMaxFileSize: Yii::$app->params['image_upload_size'];
//$dataEdit = true;
//if (!$model->isNewRecord) {
//    $oldImageInfo = pathinfo($model->$fieldName);
//    if ($oldImageInfo['extension'] == 'gif') {
//        $dataEdit = false;
//    }
//}
?>
    <div class="form-group field-<?= $itemName; ?>-image_path <?= ($model->getErrors($fieldName))? 'has-error': '' ?>" data-module="ui/Demo">
        <label class="control-label"><?= $fieldLabel; ?></label>
        <div class="clearfix"></div>

        <div class="slim my-slim"
             data-ratio="<?= $dataRatio; ?>"
             data-label="<?php echo Yii::t('backend', 'Click để chọn ảnh hoặc kéo ảnh vào đây') ?>"
             data-min-size="<?= $dataMinSize?>"
             data-size="<?= $dataSize; ?>"
             data-will-save="<?= ($dataWillSave) ?>"
             data-will-remove="<?= ($dataWillRemove) ?>"
             data-force-size="<?= $dataForceSize; ?>"
             data-save-initial-image="false"
             data-status-file-size="<?php echo Yii::t('backend', 'Dung lượng tối đa $0 MB') ?>"
             data-status-file-type="<?php echo Yii::t('backend', 'Không đúng định dạng cho phép') ?>"
             data-button-edit-label="<?php echo Yii::t('backend', 'Sửa') ?>"
             data-button-remove-label="<?php echo Yii::t('backend', 'Xoá') ?>"
             data-button-cancel-label="<?php echo Yii::t('backend', 'Huỷ') ?>"
             data-button-confirm-label="<?php echo Yii::t('backend', 'Xác nhận') ?>"
             data-max-file-size="<?php echo $dataMaxFileSize ?>"
             data-did-load="disableCrop"
             data-instant-edit="true"

             style="margin-left: 0">
            <?php if ($model->$fieldName): ?>
                <?php //$funcName = 'get'. \yii\helpers\Inflector::camelize($fieldName); ?>
                <img src="<?= $model->$fieldName. '?t='. time() ?>" alt="" />
            <?php endif; ?>

            <input id="slim-file-<?= $fieldName; ?>" class="my-slim-file" type="file" name="<?= $fieldName; ?>[]" accept="<?= $accept?>" />
        </div>
        <div class="help-block" style="padding-left: 210px"><?= Yii::t('backend', 'Crop Editor is <b>not available</b> for <b>GIF</b> image');?>

        </div>
        <div class="clearfix"></div>
        <div class="help-block"><?= $model->getErrors('image_url')[0] ?></div>
    </div>

    <input type="hidden" id="change_<?= $fieldName?>" name="change_<?= $fieldName; ?>" value="" />

    <script>
        function <?= $dataWillRemove ?>(data, ready) {
            $('#change_<?= $fieldName; ?>').val('1');
            ready(data);
        }

        function disableCrop(file, image, meta) {
            console.log($(file).attr('type'));
            if ($(file).attr('type') == 'image/gif') {
                // return "You cannot edit!"
                // console.log("UPLOAD GIF");
            }
            return true;
        }
    </script>

<?php
$this->registerJs(<<<JS

    $('#slim-file-$fieldName').on('change', function() {
        var y = $(this).clone();
        y.attr("name", "ORG_IMAGE_$fieldName");
        y.attr("class", "clone-image");
        y.insertAfter($(this));
    })
JS
);
?>
<div class="clearfix"></div>
