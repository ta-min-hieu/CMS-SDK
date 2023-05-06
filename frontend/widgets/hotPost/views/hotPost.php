<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if (count($postList)): ?>
    <div id="hot-post" class="box post-list-1 row">
        <h2 class="t col-md-12">
            <a href="<?= Url::to('post/index'); ?>">
                <?= Yii::t('frontend', 'Tin tức'); ?>
            </a>
        </h2>
        <?php foreach ($postList as $index => $post): ?>

            <div class="col-md-4 col-sm-4 col-xs-4 post-item">
                <h3 class="">
                    <a href="<?= $post->getWebDetailUrl(); ?>" title="<?= Html::encode($post->title); ?>">
                        <?= Html::encode($post->title); ?>
                    </a>
                </h3>

                <a class="img-thumbnail img-wrapper" href="<?= $post->getWebDetailUrl(); ?>"
                   title="<?= Html::encode($post->title); ?>">
                    <img class="img-responsive " src="<?= $post->getImageUrl(); ?>"
                         alt="<?= Html::encode($post->title); ?>"/>
                </a>
                <p class="post-short-desc">
                    <?= Html::encode(\yii\helpers\StringHelper::truncateWords($post->short_desc, 35)); ?>
                </p>
                <a class="readmore" href="<?= $post->getWebDetailUrl(); ?>"><?= Yii::t('frontend', 'Chi tiết'); ?></a>
                <div class="clearfix"></div>
            </div>

        <?php endforeach; ?>
        <div class="clearfix"></div>
    </div>
<?php endif; ?>
