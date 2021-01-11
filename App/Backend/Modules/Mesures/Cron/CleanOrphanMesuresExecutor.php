<?php

namespace App\Backend\Modules\Mesures\Cron;

use Entity\Scenario\ScenarioSequence;
use Entity\Scenario\SequenceAction;
use Exception;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class CleanOrphanMesuresExecutor
 * @package App\Backend\Modules\Scenarios\Cron
 */
class CleanOrphanMesuresExecutor implements ExecutorInterface
{
    /** @var Managers */
    protected $managers;

    /** @var \Model\MesuresManagerPDO */
    protected $mesuresManager;

    /**
     * CleanOrphanMesuresExecutor constructor.
     */
    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->mesuresManager = $this->managers->getManagerOf('Mesures');
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        echo $this->getDescription();
        $removedRows = $this->mesuresManager->removeOrphanRows();
        echo "Removed $removedRows orphans rows from mesures table" . PHP_EOL;
    }

    public function getDescription()
    {
        return 'Repair Scenario Module orphan relations between tables' . PHP_EOL;
    }
}
