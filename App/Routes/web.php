<?php

$cms->router->before('GET|POST', '/', 'Middlewares\MiddlewareAuth@isLogin');
$cms->router->before('GET|POST', '/order', 'Middlewares\MiddlewareAuth@isLogin');
//$cms->router->before('GET|POST', '/login', 'Middlewares\MiddlewareAuth@isLogin');

$cms->router->get('/', 'Controllers\Home@Index');

$cms->router->post('/addToCart', 'Controllers\Home@AddToCart');
$cms->router->post('/decreaseFromCart', 'Controllers\Order@decreaseFromCart');
$cms->router->post('/removeFromCart', 'Controllers\Order@removeFromCart');

$cms->router->post('/checkCoupon', 'Controllers\Order@checkCoupon');
$cms->router->post('/finalizeOrder', 'Controllers\Order@finalizeOrder');

// Login Page
$cms->router->get('/login', 'Controllers\Auth@Index');
// Auth
$cms->router->post('/login', 'Controllers\Auth@Login');
$cms->router->get('/logout', 'Controllers\Auth@Logout');

$cms->router->get('/order', 'Controllers\Order@Index');
$cms->router->get('/user/orders', 'Controllers\User@Orders');

