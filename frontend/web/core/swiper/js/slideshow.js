var slide = new Swiper('#main-slideshow', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    centeredSlides: true,
    speed: 400,
    autoplay: {
        delay: 3000,
        disableOnInteraction: true,
    },
    pagination: {
        enabled: true,
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        enabled: true,
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});