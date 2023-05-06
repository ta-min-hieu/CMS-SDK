<?php
use \frontend\models\SystemSetting;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\HomeAsset;
use common\helpers\Helpers;
HomeAsset::register($this);
$title = SystemSetting::getConfigByKey('SEO_HOME_TITLE');

?>
<?= $this->render('@app/views/layouts/partials/_seo', [
    'title' => $title ? $title : $this->title,
    'description' => SystemSetting::getConfigByKey('SEO_HOME_DESC'),
    'keywords' => SystemSetting::getConfigByKey('SEO_HOME_KEYWORDS'),
    'image' => 'http:///images/logo-retina.png',
    'url' => Yii::$app->request->getUrl(),
]); ?>

<?php foreach ($collections as $collection) : ?>
<?= $this->render('@app/views/media/_collection', ['collection' => $collection]) ?>
<?php endforeach ?>