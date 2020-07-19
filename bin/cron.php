<?php

use OCFram\PDOFactory;

require_once __DIR__ . '/../setup.php';

$app = new \App\Backend\BackendApplication();

PDOFactory::setPdoAddress($_ENV['DB_PATH']);
$pdo = PDOFactory::getSqliteConnexion();

$crontab = [
    'purge_old_node_log_rows' => [
        'expression' => '10 0 * * *',
        'executor' => '\App\Backend\Modules\Node\Log\Cron\PurgeOldExecutor'
    ]
];

$launcher = new \Sldevand\Cron\Launcher($crontab);
$launcher->launch();
