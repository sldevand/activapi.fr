<?php

namespace App\Backend\Modules\Crontab\Setup;

use Entity\Crontab\Crontab;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Api\DataSetupInterface;

/**
 * Class InitCrontab
 * @package App\Backend\Modules\Crontab\Setup
 */
class InitCrontab implements DataSetupInterface
{
    /** @var Managers */
    protected $managers;

    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
    }

    public function execute()
    {
        $crontab = [
            'purge_old_node_log_rows' => [
                'expression' => '10 0 * * *',
                'executor' => '\App\Backend\Modules\Node\Cron\PurgeOldExecutor'
            ],
            'remove_mesures_table_orphan_rows' => [
                'expression' => '15 0 * * *',
                'executor' => '\App\Backend\Modules\Mesures\Cron\CleanOrphanMesuresExecutor'
            ],
            'scheduled_scenarios_executor' => [
                'expression' => '* * * * *',
                'executor' => '\App\Backend\Modules\Scenarios\Cron\ScenariosExecutor',
                'args' => ['app']
            ],
            'check_sensors_activity' => [
                'expression' => '* * * * *',
                'executor' => '\App\Backend\Modules\Sensors\Cron\CheckSensorActivityExecutor',
                'args' => ['app']
            ],
            'check_thermostat_power' => [
                'expression' => '* * * * *',
                'executor' => '\App\Backend\Modules\Thermostat\Cron\CheckThermostatPower',
                'args' => ['app']
            ]
        ];

        /** @var \Model\Crontab\CrontabManagerPDO $crontabManager */
        $crontabManager = $this->managers->getManagerOf('Crontab\Crontab');
        foreach ($crontab as $name => $taskData) {
            $taskData['name'] = $name;
            $taskData['active'] = 1;
            $taskData['args'] = $taskData['args'] ?? [];
            $taskData['args'] = json_encode($taskData['args']);
            $task = new Crontab($taskData);
            $crontabManager->save($task);
        }
    }
}
