<?php

require_once __DIR__ . '/../setup.php';

if (!isset($_GET['app']) || !file_exists(__DIR__ . '/../App/' . $_GET['app'])) {
    $_GET['app'] = DEFAULT_APP;
}

$appClass = 'App\\' . $_GET['app'] . '\\' . $_GET['app'] . 'Application';
$app = new $appClass();

$config = new \OCFram\Config($app);
\OCFram\PDOFactory::setPdoAddress($config->getEnv('DB_PATH'));

$app->run();
