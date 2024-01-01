<?php

namespace App\Backend\Modules\Scenarios\Cron;

use Exception;
use Entity\Scenario\Action;
use Model\ActionneursManagerPDO;
use Entity\Scenario\SequenceAction;
use Model\Scenario\ActionManagerPDO;
use Sldevand\Cron\ExecutorInterface;
use Entity\Scenario\ScenarioSequence;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\ScenarioManagerPDOFactory;
use Model\Scenario\ScenarioSequenceManagerPDO;

/**
 * Class RepairDatabaseExecutor
 * @package App\Backend\Modules\Scenarios\Cron
 */
class RepairDatabaseExecutor implements ExecutorInterface
{
    protected ScenarioManagerPDOFactory $scenarioManagerPDOFactory;
    protected ActionneursManagerPDO $actionneursManager;
    protected ActionManagerPDO $actionManager;
    protected SequenceActionManagerPDO $sequenceActionManager;
    protected SequencesManagerPDO $sequencesManager;
    protected ScenarioSequenceManagerPDO $scenarioSequenceManager;
    protected ScenariosManagerPDO $scenariosManager;

    /**
     * RepairDatabaseExecutor constructor.
     */
    public function __construct()
    {
        $this->scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
        $this->actionneursManager = $this->scenarioManagerPDOFactory->getActionneursManager();
        $this->actionManager = $this->scenarioManagerPDOFactory->getActionManager();
        $this->sequenceActionManager = $this->scenarioManagerPDOFactory->getSequenceActionManager();
        $this->sequencesManager = $this->scenarioManagerPDOFactory->getSequencesManager();
        $this->scenarioSequenceManager = $this->scenarioManagerPDOFactory->getScenarioSequenceManager();
        $this->scenariosManager = $this->scenarioManagerPDOFactory->getScenariosManager();
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
        $actions = $this->actionManager->getAll();
        /** @var Action $action */
        foreach ($actions as $action) {
            if (!$this->actionneursManager->getUnique($action->getActionneurId())) {
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
            } catch (Exception) {
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
            } catch (Exception) {
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
