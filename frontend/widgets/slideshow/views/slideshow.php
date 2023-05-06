<?php if (count($slideshowList)): ?>
    <div id="main-slideshow" class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($slideshowList as $index => $adv): ?>
                <div class="swiper-slide">
                    <a target="_blank" href="<?= ($adv->LINK)? $adv->LINK: '#no-link' ?>">
                        <img class="img-fluid" src="<?= $adv->getImagePath($width,$height); ?>" />
                    </a>
                </div>

            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

<?php endif; ?>
