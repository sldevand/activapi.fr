<?php

namespace App\Backend\Modules\Node\Log\Cron;

use DateTime;
use Model\Log\LogManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class PurgeOldExecutor
 * @package App\Backend\Modules\Node\Log\Cron
 */
class PurgeOldExecutor implements ExecutorInterface
{
    const MAX_NUMBER_OF_ROWS = 30000;

    /** @var Managers */
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
        echo $this->getDescription();

        /** @var LogManagerPDO $logManager */
        $logManager = $this->managers->getManagerOf('Log\Log');

        if ($logManager->count() > self::MAX_NUMBER_OF_ROWS) {
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

    public function getDescription()
    {
        return "Delete the node logs older than three days" . PHP_EOL;
    }
}
