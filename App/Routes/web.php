<?php

// session login kontrolü
$cms->router->before('GET|POST', '/', 'Middlewares\MiddlewareAuth@isLogin');
$cms->router->before('GET|POST', '/login', 'Middlewares\MiddlewareAuth@returnBack');
$cms->router->before('GET|POST', '/cart', 'Middlewares\MiddlewareAuth@isLogin');
//$cms->router->before('GET|POST', '/login', 'Middlewares\MiddlewareAuth@isLogin');

$cms->router->get('/', 'Controllers\Home@Index');

// sepet işlemleri
$cms->router->get('/cart', 'Controllers\Cart@Index');
$cms->router->post('/addToCart', 'Controllers\Home@AddToCart');
$cms->router->post('/decreaseFromCart', 'Controllers\Cart@decreaseFromCart');
$cms->router->post('/removeFromCart', 'Controllers\Cart@removeFromCart');
$cms->router->post('/checkCoupon', 'Controllers\Cart@checkCoupon');
$cms->router->post('/finalizeOrder', 'Controllers\Cart@finalizeOrder');

// Login Page
$cms->router->get('/login', 'Controllers\Auth@Index');
// Auth
$cms->router->post('/login', 'Controllers\Auth@Login');
$cms->router->get('/logout', 'Controllers\Auth@Logout');

// user sayfası işlemleri
$cms->router->mount('/user', function () use ($cms) {
    $cms->router->before('GET|POST', '/.*', 'Middlewares\MiddlewareAuth@isLogin');
    $cms->router->get('/', 'Controllers\User@Index');
    $cms->router->post('/edit', 'Controllers\User@EditProfile');
    $cms->router->post('/password', 'Controllers\User@ChangePassword');
    $cms->router->get('/orders', 'Controllers\User@showOrders');
    $cms->router->post('/orders/cancelOrder', 'Controllers\User@cancelOrder');
    $cms->router->get('/orders/details/([0-9]+)', 'Controllers\User@OrderDetails');
    $cms->router->get('/orders/details/print/([0-9]+)', 'Controllers\User@PrintDetails');
});