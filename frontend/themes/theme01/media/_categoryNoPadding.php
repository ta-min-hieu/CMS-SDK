<?php
use yii\helpers\Url;
?>
<!-- SESSION -->
<div class="container-fluid pb-4">
    <!-- Heading-->
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1">
        <h2 class="size-24 fw-bold mb-0 pt-3 me-2"><?= $category->cate_name ?></h2>
        <div class="pt-3">
            <a href="<?= Url::to(['media/category-detail', 'id' => $category->id]) ?>" class="l-more f-base">TẤT CẢ</a>
        </div>
    </div>
    <!-- Grid-->
    <div class="row pt-3 mx-n2 align-items-stretch">
        <?php foreach ($category->songs as $song) : ?>
        <!-- Product-->
        <div class="col-lg-2 col-md-3 col-sm-6 px-2 d-flex">
            <div class="card product-card-alt w-100">
                <div class="product-thumb">
                    <div class="ratio ratio-16x9"><img src="<?= $song->getThumbnailUrl() ?>" class="rounded w-100" alt="<?= $song->song_name ?>"></div>
                    <a href="#" class="card-img-overlay d-flex justify-content-center align-items-center">
                        <h6 class="mb-0 text-truncate-multi h6 mb-0"><?= $song->song_name ?></h6>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>