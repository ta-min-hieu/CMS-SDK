<?php 
use \yii\helpers\Url;
?>
<div class="modal-header">
    <h5 class="modal-title" ><?= Yii::t('frontend', 'Thông báo') ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div id="modal-body" class="modal-body">
    <?= $this->render('@app/views/layouts/partials/viewMoreContent', [
        'contentStatusCode' => $status,
        'divClass' => 'notice',
        'contentId' => $contentId,
    ])?>
    <div class="clearfix"></div>
</div>