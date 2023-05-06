<?php
use yii\helpers\Url;
$modalId = 'createPlaylistModal';
?>
<aside class="offcanvas offcanvas-expand bg-black" id="components-nav">
    <!-- Offcanvas header -->
    <div class="offcanvas-header bg-transparent">
        <a class="navbar-brand py-1" href="/">
            <img src="/img/logo.svg" width="132">
        </a>
        <button class="btn-close btn-close-white d-md-none mt-n2" type="button" data-bs-dismiss="offcanvas"
        aria-label="Close"></button>
    </div>
    <!-- Offcanvas body -->
    <div class="offcanvas-body py-0" data-simplebar data-simplebar-auto-hide="true">
        <div class="widget widget-links widget-light mb-4 pb-4">
            <!-- List 01 -->
            <ul id="listMenuLeft" class="widget-list">
                <li class="widget-list-item <?= $this->context->route == 'site/index' ? ' active' : '' ?>"><a class="widget-list-link" href="<?= Url::to(['site/index']) ?>"><i class="ci-u-user ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Khám phá') ?></span></a></li>
                <li class="widget-list-item "><a class="widget-list-link" href="#"><i class="ci-u-disc ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Cá nhân') ?></span></a></li>
                <li class="widget-list-item "><a class="widget-list-link" href="#"><i class="ci-u-library ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Thư viện') ?></span></a></li>
                <li class="border-bottom border-light my-2"></li>
                <li class="widget-list-item <?= $this->context->route == 'media/lastest-songs' ? ' active' : '' ?>"><a class="widget-list-link" href="<?= Url::to(['media/lastest-songs']) ?>"><i class="ci-u-music ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Nhạc mới') ?></span></a></li>
                <li class="widget-list-item <?= $this->context->route == 'media/billboard' ? ' active' : '' ?>"><a class="widget-list-link" href="<?= Url::to(['media/billboard']) ?>"><i class="ci-u-chart ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Xếp hạng') ?></span></a></li>
                <li class="widget-list-item <?= $this->context->route == 'media/categories' ? ' active' : '' ?>"><a class="widget-list-link" href="<?= Url::to(['media/categories']) ?>"><i class="ci-u-genre ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Thể loại') ?></span></a></li>
                <li class="border-bottom border-light my-2"></li>
                <li class="widget-list-item ">
                    <a class="widget-list-link" data-toggle="modal" data-target="#<?= $modalId ?>" href='#' data-href="<?= \yii\helpers\Url::to(['media/create-playlist-popup']) ?>"><i class="ci-u-create-playlist ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Tạo playlist') ?></span></a></li>
                <li class="widget-list-item "><a class="widget-list-link" href="#"><i class="ci-u-like-outline ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Yêu thích') ?></span></a></li>
                <li class="my-3"><a href="#"><img src="/img/vip.png"></a></li>
                <li class="widget-list-item "><a class="widget-list-link" href="#"><i class="ci-u-download ci-custom me-2"></i><span class="text-truncate"><?= Yii::t('frontend', 'Tải app') ?></span></a></li>
                <li class="border-bottom border-light my-2"></li>
            </ul>
            <!-- List 02 (Only mobile)-->
            <ul class="widget-list d-md-none">
                <li class="widget-list-item">
                    <a class="widget-list-link" href="#"><span class="text-truncate"><?= Yii::t('frontend', 'Danh sách chặn') ?></span></a>
                </li>
                <li class="widget-list-item">
                    <a class="widget-list-link" href="#"><span class="text-truncate"><?= Yii::t('frontend', 'Chất lượng nhạc') ?></span></a>
                </li>
                <li class="widget-list-item">
                    <a class="widget-list-link" href="#"><span class="text-truncate"><?= Yii::t('frontend', 'Trình phát nhạc') ?></span></a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<!-- Modal -->
<div id="createPlaylistModal2" class="modal fade show" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content" style="background-color: #444444; border: none;">
        </div>
    </div>
</div>
<?= $this->render('@app/views/layouts/partials/_modal', ['id' => $modalId]) ?>
<?php $this->registerJs(<<<EOF
$(".modal").on("show.bs.modal", function(){
    var dataTarget = "#" + $(this).attr('id');
    var button = $('[data-target="' + dataTarget + '"]').first();
    $(this).find('.modal-content').load($(button).data('href'));
});
EOF
) ?>