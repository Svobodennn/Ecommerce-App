<?php
$cms->router->setNamespace('App');

require BASEDIR.'/App/Routes/web.php';

$cms->router->run();