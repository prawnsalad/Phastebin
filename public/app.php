<?php

	$loader = require '../vendor/autoload.php';
	$config = require '../config/config.php';

	$app = new \Slim\Slim($config);
	$phastebin = new \Phastebin\Phastebin($app);

	$app->run();
