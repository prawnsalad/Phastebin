<?php

	$loader = require '../vendor/autoload.php';
	$app = new \Slim\Slim();

	$phastebin = new \Phastebin\Phastebin($app);

	$app->run();
