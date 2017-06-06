<?php

include __DIR__ . '/../vendor/autoload.php';

$config = new \Karriere\Mocky\Configuration(__DIR__ . '/../config/mocky.php');

$mocky = new \Karriere\Mocky\Mocky($config);

$mocky->run();
