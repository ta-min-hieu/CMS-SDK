<?php
/* @var $this View */
/* @var $content string */

use \common\components\toast\AwsAlertToast;
use awesome\backend\widgets\AwsLayoutMenu;
use backend\assets\AppAsset;
use backend\components\common\MenuHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use kartik\dialog\Dialog;
use common\helpers\Helpers;
use \backend\models\User;
use \yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--        <meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta http-equiv="X-UA-Compatible" content="IE=11; IE=10; IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
<div id="page-loading">
    <img src="/img/ajax-loader3.gif" />
</div>
<?php $this->beginBody() ?>
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a class="logo-default" href="/">
                    <img src="/img/logo.png?v=1.22" alt="<?= Yii::$app->params['short_system_name']; ?>" class="logo-default"/>

                </a>
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <!-- li class="dropdown dropdown-extended dropdown-notification">
                        <a href="#">
                            <i class="icon-bell"></i> <span class="badge badge-default"> 7 </span>
                        </a>
                    </li -->
                    <?php /*<li class="change-language">
                                <a class="<?= (Yii::$app->language == 'vi')? 'active': ''; ?>" href="<?= Url::to(['site/change-language', 'lang' => 'vi']); ?>">
                                    <img src="/img/vietnam-flag-icon-32.png" />
                                </a>
                            </li>
                            <li class="change-language">
                                <a class="<?= (Yii::$app->language == 'jp')? 'active': ''; ?>"  href="<?= Url::to(['site/change-language', 'lang' => 'jp']); ?>">
                                    <img src="/img/japan-flag-icon-32.png" />
                                </a>

                            </li>
                            */?>
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <img alt="" class="img-circle" src="/img/avatar12.jpg"/>
                            <span class="username username-hide-on-mobile">
                                                <?php echo (!Yii::$app->user->isGuest) ? Html::encode(Yii::$app->user->identity->username) : 'Guest'; ?>
                                            </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <?php if (!\Yii::$app->user->isGuest): ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['/user-profile/update', 'id' => Yii::$app->user->identity->id]) ; ?>">
                                        <i class="icon-user"></i><?= Yii::t('backend', "Thông tin cá nhân") ?></a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="/logout">
                                        <i class="icon-key"></i><?= Yii::t('backend', "Log Out") ?>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="/login">
                                        <i class="icon-key"></i><?= Yii::t('backend', "Log In") ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <?php if (!\Yii::$app->user->isGuest): ?>
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="/logout" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- END QUICK SIDEBAR TOGGLER -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"></div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="profile-sidebar-portlet">

                    <div class="profile-userpic">
                        <img src="/img/avatar12.jpg" class="img-responsive" alt="">
                    </div>
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name"><?= Html::encode(Yii::$app->user->identity->username);?></div>

                        <div class="profile-usertitle-job">
                            <br />
                            <?php $otherLang =  ((Yii::$app->language == 'en')? 'es': 'en') ?>
                            <a href="<?= Url::to(['site/change-language', 'lang' => $otherLang]) ?>" >
                                <img src="/img/flag-<?= $otherLang ?>.png" style="vertical-align: middle;width: 32px;" />
                                <?= Yii::t('backend', Yii::$app->params['content_languages'][$otherLang]) ?>
                            </a>
                        </div>
                    </div>

                    <div class="profile-userbuttons">

                        <!--                                <button type="button" class="btn btn-circle green btn-sm">Follow</button>-->

                    </div>
                    <div class="clearfix"></div>
                </div>


                <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200" style="">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <li class="sidebar-toggler-wrapper hide">
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                    </li>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                    <li class="nav-item start ">
                        <a href="/" class="nav-link nav-toggle">
                            <i class="icon-home"></i>
                            <span class="title"><?= Yii::t('backend','Homepage') ?></span>
                        </a>
                    </li>
                    <?php
                    if (!Yii::$app->user->isGuest) {
                        $callback = function ($menu) {
                            if ($menu['route']) {
                                return [
                                    'label' => $menu['name'],
                                    'url' => [$menu['route']],
                                    'items' => $menu['children'],
                                    'visible' => $menu['is_active'],
                                    'icon' => $menu['icon'],
                                    'parent' => $menu['parent']
                                ];
                            } else {
                                return [
                                    'label' => $menu['name'],
                                    'items' => $menu['children'],
                                    'visible' => $menu['is_active'],
                                    'icon' => $menu['icon'],
                                    'parent' => $menu['parent']
                                ];
                            }
                        };
                        $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback, true);
                        //var_dump($items); die;
                        echo AwsLayoutMenu::widget([
                            'items' => $items,
                        ]);
                    }
                    ?>
                </ul>
                <!-- END SIDEBAR MENU -->
                <!-- END SIDEBAR MENU -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
                    <?=
                    Breadcrumbs::widget([
                        'itemTemplate' => "<li>{link}<i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></li>\n", // template for all links
                        'activeItemTemplate' => "<li>{link}</li>\n", // template for all links
                        'options' => [
                            'class' => 'page-breadcrumb '
                        ],
                        'homeLink' => [
                            'label' => Yii::t('backend', 'Home'),
                            'url' => Yii::$app->homeUrl,
                            'template' => "<li><span aria-hidden=\"true\" class=\"icon-home\"></span>{link}<i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></li>\n",
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                </div>
                <!-- END PAGE BAR -->
                <!-- BEGIN MESSAGE -->
                <?=
                AwsAlertToast::widget([
                    'position' => AwsAlertToast::POS_TOP_CENTER,
                    'timeOut' => 3000,
                ]);
                ?>
                <?= Dialog::widget(['options' => [
                    'title' => Yii::t('backend', 'Xác nhận'),
                    'type' => 'type-default'
                ]]) ?>
                <!-- END MESSAGE -->
                <!-- BEGIN MAIN CONTENT -->
                <?= $content ?>
                <!-- END MAIN CONTENT-->
                <!-- END PAGE HEADER-->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->

        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="">

        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
