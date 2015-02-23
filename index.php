<?php
require_once ('./bootstrap.php');

try {
	$router = new api\Router(configs\Config::dbConfig());
	$router->process();
} catch (Exception $e){
	$e->getMessage();
}