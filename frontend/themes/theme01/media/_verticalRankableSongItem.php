<?php

use yii\helpers\Html;
$index = $index + $dataProvider->pagination->page*$dataProvider->pagination->pageSize;
switch ($index) {
    case 0:
        $textLevelClass = "top-level";
        break;
    case 1:
        $textLevelClass = "second-level";
        break;
    case 2:
        $textLevelClass = "third-level";
        break;
    default:
        $textLevelClass = "";
        break;
}

$artistNameString = null;
$artistNames = [];
foreach ($model->artists as $artist) {
    $artistNames[] = Html::a($artist->alias_name, '#');
}
$artistNameString = implode(', ', $artistNames);
?>
<!-- Item -->
<div class="d-flex flex-nowrap justify-content-between align-items-center u-row ">
   <div class="u-col col-01">
      <div class="text-billboard d-flex align-items-center">
         <span class="text-number-decor d-flex align-items-center justify-content-center fw-bold <?= $textLevelClass ?>"><?= $index + 1 ?></span>
         <div class="text-gaph ms-3"><span class="text-up ci-caret"></span> <span class="text-level">6</span></div>
         <span class="icon-sign"><i class="ci-u-triangle"></i></span>
      </div>
   </div>
   <div class="u-col col-02">
      <a href="#">
         <div class="d-flex align-items-center">
            <div class="media-img flex-shrink-0">
               <div class="ratio ratio-1x1"><img src="<?= $model->getThumbnailUrl() ?>"></div>
            </div>
            <div class="ps-3">
               <div class="fs-md text-truncate"><?= $model->song_name ?></div>
               <div class="fs-xs"><?= $artistNameString ?></div>
            </div>
            <span class="vip-lable">VIP</span>
         </div>
      </a>
   </div>
   <div class="u-col col-03"><?= $song->duration ?></div>
   <div class="u-col col-04"><a class="d-inline-block align-middle" href="#"><i class="ci-u-lyrics size-24"></i></a><a class="d-inline-block align-middle like-toggle mx-2" href="#" data-bs-toggle="button"><i class="ci-u-like-outline size-24"></i><i class="ci-u-like-solid size-24"></i></a><a class="d-inline-block align-middle" href="#"><i class="ci-u-more size-24"></i></a></div>
</div>