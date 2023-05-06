<?php

use yii\helpers\Html;
use sjaakp\loadmore\LoadMorePager;
use yii\widgets\ListView;

?>
<!-- Table -->
<div class="u-table mt-3">
    <!-- Heading -->
    <div class="d-flex flex-nowrap justify-content-between align-items-center u-heading"></div>
    <!-- listSongTable -->
    <div>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n<div class=\"u-heading mt-2 mb-3\"></div>{pager}",
        'pager' => [
            'class' => LoadMorePager::class,
            'label' => Yii::t('frontend', 'Xem thêm<i class="ci-arrow-right fs-ms ms-1"></i>'),
            'options' => [
                'class' => 'btn btn-sm rounded-pill btn-outline-light',
            ],
            'indicator' => 'disabled',
        ],
        'itemView' => '@app/views/media/_verticalRankableSongItem',
        'viewParams' => ['dataProvider' => $dataProvider],
    ])  ?>
    </div>
</div>