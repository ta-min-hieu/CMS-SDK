<?php
use yii\helpers\Html;

$artistNames = [];
foreach ($album->songs as $song) {
    foreach ($song->artists as $artist) {
        if(sizeof($artistNames) < 5) {
            $artistNames[] = Html::a($artist->alias_name, '#');
        }
    }
}
$artistNameString = implode(', ', $artistNames);
?>
<!-- SESSION -->
<div class="container-fluid pb-4">
    <!-- Header -->
    <div class="d-flex align-items-end justify-content-start w-100">
        <div><img src="<?= $album->getThumbnailUrl() ?>" class="rounded img-fixed"></div>
        <div class="ps-3">
            <div class="text-muted mb-3">Playlist</div>
            <div class="h1 size-48 fw-bold"><?= $album->album_name ?></div>
            <div class="text-muted fs-xs"><?= Yii::t('frontend', '{number_of_songs} bài hát - bởi {artist_name};', ['number_of_songs' => $album->number_of_songs, 'artist_name' => $artistNameString]) ?>
                <a href="#" class="text-muted">Việt Nam</a>, <a href="#" class="text-muted">Nhạc Trẻ</a>, <a
                    href="#" class="text-muted">V-Pop</a>; <?= Yii::t('frontend', '{created_at}; {total_liked} yêu thích', ['created_at' => date('d/m/Y', strtotime($album->created_at)), 'total_liked' => $album->total_liked]) ?>
            </div>
        </div>
    </div>
    <?= $this->render('@app/views/media/_verticalSongList', ['songs' => $album->songs]) ?>
</div>