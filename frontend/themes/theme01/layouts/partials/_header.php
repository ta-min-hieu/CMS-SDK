<?php
use yii\helpers\Url;
?>
<header class="navbar fixed-top shadow-sm justify-content-start flex-nowrap navbar-dark bg-dark"
    data-scroll-header data-fixed-element>
    <div class="container-fluid">
        <!-- Toogle button -->
        <button class="navbar-toggler ms-n3 d-md-none" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#components-nav"><span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-md-none me-0 me-md-2" href="/"><img src="/img/logo.svg" alt="Umusic"></a>
        <div class="d-md-flex flex-nowrap">
            <!-- Button -->
            <div class="d-none d-md-flex flex-nowrap">
                <button type="button" class="btn btn-sm rounded-pill btn-nav">
                <i class="ci-arrow-left"></i>
                </button>
                <button type="button" class="btn btn-sm rounded-pill btn-nav mx-3">
                <i class="ci-arrow-right"></i>
                </button>
            </div>
            <!-- Collapse -->
            <div class="collapse popup-search" id="collapseSearch">
                <!-- Search -->
                <div class="input-group">
                    <i class="ci-search position-absolute top-50 start-0 translate-middle-y text-muted fs-base ms-3"></i>
                    <input class="form-control form-control-sm ps-5 rounded-pill border-0" type="text"
                    placeholder="<?= Yii::t('frontend', 'Tìm kiếm bài hát, nghệ sĩ, MV ...') ?>" />
                </div>
                <!-- Toggle search (Only mobile)-->
                <a href="#collapseSearch" class="navbar-tool d-md-none ms-3" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="collapseSearch">
                    <i class="ci-close"></i>
                </a>
            </div>
        </div>
        <!-- Admin toolbar -->
        <div class="navbar-toolbar d-flex align-items-center">
            <!-- Link -->
            <div class="d-none d-md-flex flex-nowrap">
                <a href="<?= Url::to(['site/sign-up']) ?>" class="btn btn-sm link-light me-2"><?= Yii::t('frontend', 'Đăng ký') ?></a>
                <a href="<?= Url::to(['site/sign-in']) ?>" class="btn btn-light btn-sm rounded-pill"><?= Yii::t('frontend', 'Đăng nhập') ?></a>
            </div>
            <!-- Toggle search (Only mobile)-->
            <a href="#collapseSearch" class="navbar-tool d-md-none" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="collapseSearch">
                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-search"></i></div>
            </a>
            <!-- Dropdown (Only web)-->
            <div class="btn-group dropdown ms-md-3">
                <span class="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle rounded-pill d-none d-md-inline-block">
                    <?= Yii::t('frontend', 'Cài đặt') ?>
                    </button>
                </span>
                <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end py-0">
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Danh sách chặn') ?></a>
                    <div class="dropdown-divider m-0"></div>
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Chất lượng nhạc') ?></a>
                    <div class="dropdown-divider m-0"></div>
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Trình phát nhạc') ?></a>
                </div>
            </div>
            <!-- Dropdown -->
            <div class="btn-group dropdown ms-md-3">
                <span class="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle rounded-pill d-none d-md-inline-block">
                    Username
                    </button>
                    <a class="navbar-tool d-md-none me-n2" href="#">
                        <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-user"></i></div>
                    </a>
                </span>
                <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end py-0">
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Tài khoản') ?></a>
                    <div class="dropdown-divider m-0"><?= Yii::t('frontend', '</') ?>div>
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Thông tin chung') ?></a>
                    <div class="dropdown-divider m-0"></div>
                    <a href="#" class="dropdown-item"><?= Yii::t('frontend', 'Đăng xuất') ?></a>
                </div>
            </div>
        </div>
    </div>
</header>