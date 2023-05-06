<?php

use yii\helpers\Html;

?>
<!-- Dark table with hoverable rows -->
<div class="u-table">
    <!-- Heading -->
    <div class="d-flex flex-nowrap justify-content-between align-items-center u-heading">
        <div class="u-col col-01"><?= Yii::t('frontend', '#') ?></div>
        <div class="u-col col-02"><?= Yii::t('frontend', 'Bài hát') ?></div>
        <div class="u-col col-03"><?= Yii::t('frontend', 'Thời lượng') ?></div>
        <div class="u-col col-04"><?= Yii::t('frontend', 'Tùy chọn') ?></div>
    </div>
    <!-- listSongTable -->
    <div>
        <?php $index = 1 ?>
        <?php foreach ($songs as $song) : ?>
        <?php
            $artistNameString = null;
            $artistNames = [];
            foreach ($song->artists as $artist) {
                $artistNames[] = Html::a($artist->alias_name, '#');
            }
            $artistNameString = implode(', ', $artistNames);
        ?>
        <!-- Item -->
        <div class="d-flex flex-nowrap justify-content-between align-items-center u-row ">
            <div class="u-col col-01">
                <div class="text-index"><span class="text-number"><?= $index++ ?></span><span class="icon-sign"><i class="ci-u-triangle"></i></span></div>
            </div>
            <div class="u-col col-02">
                <a href="#">
                    <div class="d-flex align-items-center">
                        <div class="media-img flex-shrink-0"><img src="<?= $song->getThumbnailUrl() ?>"></div>
                        <div class="ps-3">
                            <div class="fs-md text-truncate"><?= $song->song_name ?></div>
                            <div class="fs-xs"><?= $artistNameString ?></div>
                        </div>
                        <span class="vip-lable">VIP</span>
                    </div>
                </a>
            </div>
            <div class="u-col col-03"><?= $song->duration ?></div>
            <div class="u-col col-04"><a class="d-inline-block align-middle" href="#"><i class="ci-u-lyrics size-24"></i></a><a class="d-inline-block align-middle like-toggle mx-2" href="#"><i class="ci-u-like-outline size-24"></i><i class="ci-u-like-solid size-24"></i></a><a class="d-inline-block align-middle" href="#"><i class="ci-u-more size-24"></i></a></div>
        </div>
        <?php endforeach ?>
    </div>
</div>