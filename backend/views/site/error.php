<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<section class="page_404">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="col-sm-10 col-sm-offset-1  text-center">
                    <?php if (strpos($this->title, '404')) { ?>
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">404</h1>
                        </div>
                        <div class="contant_box_404">
                            <h3 class="h2">
                                Look like you're lost
                            </h3>
                            <p>the page you are looking for not avaible!</p>
                            <a href="<?= \yii\helpers\Url::to('/'); ?>" class="link_404">Go to Home</a>
                        </div>
                    <?php } elseif (strpos($this->title, '403')) { ?>
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">403</h1>
                        </div>
                        <div class="contant_box_404">
                            <h3 class="h2">
                                You... you can't have that.
                            </h3>
                            <a href="<?= \yii\helpers\Url::to('/'); ?>" class="link_404">Go to Home</a>
                        </div>
                    <?php } elseif (strpos($this->title, '500')) { ?>
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">500</h1>
                        </div>
                        <div class="contant_box_404">
                            <h3 class="h2">
                                Ooops - Error 500
                            </h3>
                            <p>Please contact your administrator</p>
                            <a href="<?= \yii\helpers\Url::to('/'); ?>" class="link_404">Go to Home</a>
                        </div>
                    <?php } else { ?>
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">An Error Occurred</h1>
                        </div>
                        <div class="contant_box_404">
                            <a href="<?= \yii\helpers\Url::to('/'); ?>" class="link_404">Go to Home</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->registerCss('
* {
    margin: 0;
    padding: 0;
}
.no-js {
    height: 100vh;
}
.page-lock {
    
    width: 100%;
    position: relative;
}

.page-lock .page-logo {
    display: none:
}
.page-lock .page-body {
    top: -10vh;
    bottom: -20vh;
    height: 100vh;
    margin-bottom: -10vh;
}

.lock-head {
   display: none;
}
.lock-body {
    width: 100%;
    margin-top: -10vh;
}

.buttonHere {
    display: none;
}

.page-footer-custom{
    display: none;
}




.page_404{ padding:40px 0; background:#fff;
}

.page_404  img{ width:100%;}

.four_zero_four_bg{
 
 background-image: url(/img/404.gif);
    height: 400px;
    background-position: center;
 }
 
 
.four_zero_four_bg h1{
    font-size:80px;
}
 
  .four_zero_four_bg h3{
			 font-size:80px;
			 }
			 
			 .link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;}
    .link_404:hover {
        text-decoration:none;
        opacity: 0.8;
    }
	.contant_box_404{ margin-top:-50px;}
');
?>