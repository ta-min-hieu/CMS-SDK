<?php
use \yii\helpers\Html;
?>
<?php $this->title = 'Danh sách tin đang theo dõi'; ?>





<div id="follow-page" class="sale-list-1 box">
    <h1 class="box-title"><?= $this->title; ?></h1>

    <div class="row">
        <?= $this->render('@app/views/story/_list3', [
            'storyList' => $listPager,
            'itemPerRow' => 4,
        ]); ?>

        <div class="col-md-12">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'maxButtonCount' => 5,
            ]);
            ?>
        </div>

        <div class="clearfix"></div>
    </div>

</div>
