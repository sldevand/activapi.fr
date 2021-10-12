<?php

use App\Backend\BackendApplication;
use OCFram\PDOFactory;
use Sldevand\Cron\Launcher;

require_once __DIR__ . '/../setup.php';

$app = new BackendApplication();

PDOFactory::setPdoAddress($_ENV['DB_PATH']);
$pdo = PDOFactory::getSqliteConnexion();

$crontab = [
    'purge_old_node_log_rows' => [
        'expression' => '10 0 * * *',
        'executor' => '\App\Backend\Modules\Node\Log\Cron\PurgeOldExecutor'
    ],
    'remove_mesures_table_orphan_rows' => [
        'expression' => '15 0 * * *',
        'executor' => '\App\Backend\Modules\Mesures\Cron\CleanOrphanMesuresExecutor'
    ],
    'scheduled_scenarios_executor' => [
        'expression' => '* * * * *',
        'executor' => '\App\Backend\Modules\Scenarios\Cron\ScenariosExecutor',
        'args' => ['app' => $app]
    ],
    'check_sensors_activity' => [
        'expression' => '* * * * *',
        'executor' => '\App\Backend\Modules\Sensors\Cron\CheckSensorActivityExecutor',
        'args' => ['app' => $app]
    ],
    'check_thermostat_power' => [
        'expression' => '* * * * *',
        'executor' => '\App\Backend\Modules\Thermostat\Cron\CheckThermostatPower',
        'args' => ['app' => $app]
    ]
];

$launcher = new Launcher($crontab);
$launcher->launch();
