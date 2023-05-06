<?php
use \yii\helpers\Html;
?>

<?= $this->render('@app/views/layouts/partials/_seo', [
    'title' => ($page->seo_title)? $page->seo_title: $page->title,
    'description' => ($page->seo_description)? $page->seo_description: $page->short_desc,
    'keywords' => $page->seo_keywords,
    'image' => $page->getImageUrl(),
    'url' => Yii::$app->request->getAbsoluteUrl(),
]);
?>
<div id="post-detail" class="box">
<!--    <h1 class="page-title"><span class="name">--><?//= Html::encode($page->title); ?><!--</span> </h1>-->
    <div id="page-body">
        <?= $page->body ;?>
        <div class="clearfix"></div>
    </div>
</div>
