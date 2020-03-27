<?php

use OCFram\PDOFactory;
use SFram\OSDetectorFactory;

include __DIR__ . '/../vendor/autoload.php';

OSDetectorFactory::begin();

$app = new \App\Backend\BackendApplication();

$key = OSDetectorFactory::getPdoAddressKey();
PDOFactory::setPdoAddress($app->config()->get($key));

$crontab = [
    'purge_old_node_log_rows' => [
        'expression' => '10 0 * * *',
        'executor' => '\App\Backend\Modules\Node\Log\Cron\PurgeOldExecutor'
    ]
];

$launcher = new \Sldevand\Cron\Launcher($crontab);
$launcher->launch();
