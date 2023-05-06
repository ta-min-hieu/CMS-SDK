<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

// yii\web\JqueryAsset::register($this);
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="revisit-after" content="1 days" />
    <meta name="ROBOTS" content="index,follow,noodp" />
    <meta name="googlebot" content="index,follow" />
    <meta name="BingBOT" content="index,follow" />
    <meta name="yahooBOT" content="index,follow" />
    <meta name="slurp" content="index,follow" />
    <meta name="msnbot" content="index,follow" />
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
    <!-- <link rel="manifest" href="/site.webmanifest" /> -->
    <link rel="mask-icon" color="#fe6a6a" href="/safari-pinned-tab.svg" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="theme-color" content="#ffffff" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <main>
        <section class="offcanvas-enabled pb-3 pb-md-4">
            <!-- Header -->
            <?= $this->render('@app/views/layouts/partials/_header') ?>
            <!-- Side navigation -->
            <?= $this->render('@app/views/layouts/partials/_sidebar') ?>
            <!-- Basic example-->
            <div class="mt-5 pt-3"></div>
            <!-- Main content -->
            <?= $content ?>
        </section>
    </main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
