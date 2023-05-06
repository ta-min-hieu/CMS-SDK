<?php

return [
    '/'=>'site/index',

//    [
//        'pattern' => '/kh',
//        'route' => 'site/index',
//        'defaults' => ['lang' => 'kh'],
//    ],
//    [
//        'pattern' => '/vi',
//        'route' => 'site/index',
//        'defaults' => ['lang' => 'vi'],
//    ],
    '/site/captcha' => 'site/captcha',
    '/change-language/<lang>' => 'site/change-language',

    '/not-found.html' => 'site/error',

    // Cac trang tinh
    '/p/<page_slug>/<lang>' => 'static-page/index',

//    '/sitemap.xml' => 'default/sitemap',
//    '<lang:\w+>/<action>' => 'site/<action>',
    '/collection/<id>' => 'collection/detail',
    '/album/<id>' => 'album/detail',
    '/playlist/<id>' => 'playlist/detail',
    '/category/<id>' => 'category/detail',
    'sign-in' => 'site/sign-in',
    'sign-in-by-phone' => 'site/sign-in-by-phone',
];