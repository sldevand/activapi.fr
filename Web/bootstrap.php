<?php

require_once __DIR__ . '/../setup.php';


if (!isset($_GET['app']) || !file_exists(__DIR__ . '/../App/' . $_GET['app'])) {
    $_GET['app'] = DEFAULT_APP;
}

use OCFram\PDOFactory;
use SFram\OSDetectorFactory;

OSDetectorFactory::begin();

$appClass = 'App\\' . $_GET['app'] . '\\' . $_GET['app'] . 'Application';
$app = new $appClass();

$key = OSDetectorFactory::getPdoAddressKey();
PDOFactory::setPdoAddress($app->config()->get($key));

$app->run();
