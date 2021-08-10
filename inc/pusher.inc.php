<?php
use Melbahja\Http2\Pusher;
$pusher = Pusher::getInstance();

$pusher->link('/static/css/index.css?v=79')
        ->link('/static/css/fa/css/all.min.css?v=1')
        ->src('/static/js/animation.js')
        ->src('/static/js/bmi.js')
        ->src('/static/js/compat.js')
        ->src('/static/js/gotv.js')
        ->src('/static/js/manipulation.js')
        ->src('/static/js/scripts.js?v=11')
        ->src('/static/js/to-top.js')
        ->img('/static/img/app-icons/apple-touch-icon-ipad.png')
        ->img('/static/img/app-icons/apple-touch-icon-ipad-retina.png')
        ->img('/static/img/app-icons/apple-touch-icon-iphone.png')
        ->img('/static/img/app-icons/apple-touch-icon-iphone-retina.png')
        ->img('/static/img/app-icons/favicon.png')
        ->img('/static/img/app-icons/icon-16.png')
        ->img('/static/img/app-icons/icon-32.png')
        ->img('/static/img/app-icons/icon-36.png')
        ->img('/static/img/app-icons/icon-64.png')
        ->img('/static/img/app-icons/icon-80.png')
        ->img('/static/img/app-icons/icon-90.png')
        ->img('/static/img/app-icons/icon-120.png')
        ->img('/static/img/app-icons/icon-128.png')
        ->img('/static/img/app-icons/icon-192.png')
        ->img('/static/img/app-icons/icon-256.png')
        ->img('/static/img/app-icons/icon-512.png')
        ->img('/static/img/app-icons/nsh_200x200.png')
        ->img('/static/img/banner.png')
        ->img('/static/img/gray_banner.png')
        ->img('/static/img/blue_banner.png')
        ->img('/static/img/new_banner.png')
        ->img('/static/img/new-banner.png')
        ->img('/static/img/cloud-banner.png')
        ->img('/static/img/fb-icon.png')
        ->img('/static/img/tw-icon.png')
        ->img('/static/img/img_desc_logo.png')
        ->img('/static/img/favicon-32x32.png');

$pusher->push();
?>