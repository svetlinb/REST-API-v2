<?php
require_once ('./bootstrap.php');

try {
	$router = new api\Router(new \configs\Config());
	$router->process();
} catch (Exception $e){
	$e->getMessage();
}