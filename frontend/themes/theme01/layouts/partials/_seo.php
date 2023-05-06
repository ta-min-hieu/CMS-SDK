<?php $this->title = $title; ?>
<?php
$this->registerMetaTag([
    'name' => 'description',
    'content' => $description
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $keywords
]);

$this->registerMetaTag([
    'name' => 'og:locale',
    'content' => 'vi_VN'
]);
$this->registerMetaTag([
    'name' => 'og:site_name',
    'content' => ''
]);
$this->registerMetaTag([
    'name' => 'og:title',
    'content' => $title,
]);
$this->registerMetaTag([
    'name' => 'og:description',
    'content' => $description
]);

?>

<?php
$this->registerLinkTag([
    'title' => $title,
    'rel' => 'canonical',
    // 'type' => 'application/rss+xml',
    'href' => ($url)? $url: Yii::$app->request->getAbsoluteUrl(),
]);?>

