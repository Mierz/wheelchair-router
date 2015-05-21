<?php 
session_start();

error_reporting (E_ALL); 

include_once ('/config.php'); 

$dbObject = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
$dbObject->exec('SET CHARACTER SET utf8');

include (SITE_PATH . DS . 'app' . DS . 'core' . DS . 'core.php'); 

$router = new Router($registry);

$registry->set ('router', $router);
$router->setPath (SITE_PATH . 'app' . DS . 'controllers');
$router->start();