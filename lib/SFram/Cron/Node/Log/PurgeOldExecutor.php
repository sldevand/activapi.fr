<?php

namespace SFram\Cron\Node\Log;

use DateTime;
use Model\Log\LogManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Cron\ExecutorInterface;

/**
 * Class PurgeOldExecutor
 * @package SFram\Cron\Node\Log
 */
class PurgeOldExecutor implements ExecutorInterface
{
    /**
     * @var Managers
     */
    protected $managers;

    /**
     * PurgeOld constructor.
     */
    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
    }

    /**
     * Delete the node logs older than three days
     *
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {
        echo "Delete the node logs older than three days" . PHP_EOL;
        /** @var LogManagerPDO $logManager */
        $logManager = $this->managers->getManagerOf('Log\Log');

        if ($logManager->count() > 30000) {
            echo 'The number of rows is too important, so we truncate the log table';
            $logManager->truncate();
            return true;
        }


        $timestamp = $this->beforeLastWeek();
        $logs = $logManager->getAll(null, $timestamp);

        if (empty($logs)) {
            echo 'Nothing to process' . PHP_EOL;
            return false;
        }

        $counter = 0;
        $counterTemp = 0;
        /** @var \Entity\Log\Log[] $logs */
        foreach ($logs as $log) {
            $logManager->delete($log->id());
            if ($counterTemp >= 1000) {
                $counterTemp = 0;
                echo $counter . ' deleted rows' . PHP_EOL;
            }

            $counter++;
            $counterTemp++;
        }

        return true;
    }

    /**
     * @return false|int
     */
    protected function beforeLastWeek()
    {
        $date = new DateTime();
        $now = $date->getTimestamp();

        $beginOfDay = strtotime("midnight", $now);
        return strtotime('-3 days', $beginOfDay);
    }
}
