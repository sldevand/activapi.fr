<?php

namespace App\Backend\Modules\Scenarios\Cron;

use Entity\Scenario\Action;
use Entity\Scenario\ScenarioSequence;
use Entity\Scenario\SequenceAction;
use Exception;
use Model\ActionneursManagerPDO;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class RepairDatabaseExecutor
 * @package App\Backend\Modules\Scenarios\Cron
 */
class RepairDatabaseExecutor implements ExecutorInterface
{
    /** @var Managers */
    protected $managers;

    /** @var ActionManagerPDO */
    protected $actionManager;

    /** @var SequenceActionManagerPDO */
    protected $sequenceActionManager;

    /** @var SequencesManagerPDO */
    protected $sequencesManager;

    /** @var ScenarioSequenceManagerPDO */
    protected $scenarioSequenceManager;

    /** @var ScenariosManagerPDO */
    protected $scenariosManager;

    /**
     * RepairDatabaseExecutor constructor.
     */
    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $actionneursManagerPDO = $this->managers->getManagerOf('Actionneurs');
        $this->actionManager = $this->managers
            ->getManagerOf('Scenario\Action', ['actionneursManagerPDO' => $actionneursManagerPDO]);

        $this->sequenceActionManager = $this->managers
            ->getManagerOf('Scenario\SequenceAction');

        $this->sequencesManager = $this->managers
            ->getManagerOf('Scenario\Sequences', [
                'sequenceActionManagerPDO' => $this->sequenceActionManager,
                'actionManagerPDO' => $this->actionManager
            ]);

        $this->scenarioSequenceManager = $this->managers
            ->getManagerOf('Scenario\ScenarioSequence');

        $this->scenariosManager = $this->managers
            ->getManagerOf('Scenario\Scenarios', [
                'sequencesManagerPDO' => $this->sequencesManager,
                'scenarioSequenceManagerPDO' => $this->scenarioSequenceManager
            ]);
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        echo $this->getDescription();

        $this->cleanActionsWithNoActionneur();
        $this->cleanSequenceActions();
        $this->cleanScenarioSequences();
    }

    /**
     * @throws Exception
     */
    public function cleanActionsWithNoActionneur()
    {
        $actions = $this->actionManager->getRows();
        /** @var Action $action */
        foreach ($actions as $action) {
            try {
                $this->actionManager->getUnique($action->getActionneurId());
            } catch (Exception $exception) {
                $this->actionManager->delete($action->id());
                echo 'Deleted sequence_action row : ' . $action->id() . PHP_EOL;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function cleanSequenceActions()
    {
        $sequencesActions = $this->sequenceActionManager->getAll();
        /** @var SequenceAction $sequenceAction */
        foreach ($sequencesActions as $sequenceAction) {
            try {
                $this->sequencesManager->getUnique($sequenceAction->getSequenceId());
                $this->actionManager->getUnique($sequenceAction->getActionId());
            } catch (Exception $exception) {
                $this->sequenceActionManager->delete($sequenceAction->id());
                echo 'Deleted sequence_action row : ' . $sequenceAction->id() . PHP_EOL;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function cleanScenarioSequences()
    {
        $scenarioSequences = $this->scenarioSequenceManager->getAll();
        /** @var ScenarioSequence $scenarioSequence */
        foreach ($scenarioSequences as $scenarioSequence) {
            try {
                $this->sequencesManager->getUnique($scenarioSequence->getSequenceId());
                $this->scenariosManager->getUnique($scenarioSequence->getScenarioId());
            } catch (Exception $exception) {
                $this->scenarioSequenceManager->delete($scenarioSequence->id());
                echo 'Deleted scenario_sequence row : ' . $scenarioSequence->id() . PHP_EOL;
            }
        }
    }

    public function getDescription()
    {
        return 'Repair Scenario Module orphan relations between tables' . PHP_EOL;
    }
}
