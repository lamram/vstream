<?php

use library\route;

$route = new route;

$route->set('/', 'browse.html');
$route->set('browse', 'browse.html');
$route->set('register', 'register.html');
$route->set('login', 'login.html');
$route->set('account', 'account.html');

$route->set('test', 'controller->test');