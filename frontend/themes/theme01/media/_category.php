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
            <div class="card product-card-alt">
                <div class="product-thumb">
                    <div class="position-relative w-100">
                        <div class="product-card-actions"><a class="d-inline-block align-middle like-toggle" href="#" data-bs-toggle="button"><i class="ci-u-like-outline size-24"></i><i class="ci-u-like-solid size-24"></i></a><button type="button" class="btn btn-warning rounded-pill btn-icon mx-3 play-toggle" data-bs-toggle="button"><i class="ci-u-triangle"></i><i class="ci-u-pause-sign"></i></button><a class="d-inline-block align-middle" href="#"><i class="ci-u-more size-24"></i></a></div>
                        <a class="product-thumb-overlay rounded" href="song-detail.html"></a>
                        <div class="ratio ratio-1x1"><img src="<?= $song->getThumbnailUrl() ?>" class="rounded w-100" alt="<?= $song->song_name ?>"></div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="product-title mb-0 text-truncate-multi h6"><a href="#"><?= $song->song_name ?></a></h6>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>