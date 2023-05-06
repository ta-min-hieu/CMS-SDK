<?php
use \yii\helpers\Html;
?>
<?php $this->title = 'Lịch sử đọc tin'; ?>


<div id="history" class=" box">
    <h1 class="box-title">Lịch sử đọc tin</h1>
    <div class="panel panel-info">
        <div class="panel-body row sale-list-2">
            <?php if (count($listPager)):  ?>
                <?php foreach ($listPager as $hkey => $history): ?>
                    <div class="sale-item col-md-6 col-sm-6">
                        <a class="sale-img pull-left " title="<?= Html::encode($history->story->name); ?>" href="<?= $history->story->getWebDetailUrl(); ?>">
                            <img class="" src="<?= $history->story->getImageUrl(); ?>" />
                        </a>
                        <div class="sale-info">
                            <a class="story-link" title="<?= Html::encode($history->story->name); ?>" href="<?= $history->story->getWebDetailUrl(); ?>">
                                <?= Html::encode($history->story->name); ?>
                                <?php if($history->story->other_name): ?>
                                    (<?= Html::encode($history->story->other_name); ?>)
                                <?php endif;?>
                            </a>
                            <br />
                            Chương: <a class="chapter-link" title="<?= Html::encode($history->story->name); ?> - <?= Html::encode($history->chapter->name); ?>" href="<?= $history->chapter->getWebDetailUrl(); ?>">
                                <?= Html::encode($history->chapter->name); ?>
                            </a>
                            <br />
                            Thời điểm đọc: <span class="date"><?= date('d/m/Y H:i', strtotime($history->created_at)) ?></span>
                            <br />
                            Thể loại:
                            <?php
                            $categoryList = $history->story->getActiveCategoryList();
                            $countCategory = count($categoryList);

                            if ($countCategory):
                                ?>

                                <?php foreach ($categoryList as $key => $category): ?>
                                <a href="<?= $category->getWebDetailUrl(); ?>" title="<?= \yii\helpers\Html::encode($category->name) ?>">
                                    <?= \yii\helpers\Html::encode($category->name) ?>
                                </a>
                                <?php echo ($key < $countCategory-1)? ", ": ''; ?>

                            <?php endforeach; ?>
                            <?php else: ?>
                                N/A
                            <?php endif;?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php if (($hkey + 1) % 2 == 0): ?>
                        <div class="clearfix hidden-xs"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>


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


</div>
