<?php

$cms->router->before('GET|POST', '/', 'Middlewares\MiddlewareAuth@isLogin');
//$cms->router->before('GET|POST', '/login', 'Middlewares\MiddlewareAuth@isLogin');

$cms->router->get('/', 'Controllers\Home@Index');

$cms->router->post('/addToCart', 'Controllers\Home@AddToCart');

// Login Page
$cms->router->get('/login', 'Controllers\Auth@Index');
// Auth
$cms->router->post('/login', 'Controllers\Auth@Login');
$cms->router->get('/logout', 'Controllers\Auth@Logout');


