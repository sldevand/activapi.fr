<?php

namespace App\Backend\Modules;

use Model\ActionneursManagerPDO;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use OCFram\BackController;

/**
 * Class AbstractScenarioManagersController
 * @package App\Backend\Modules\Scenarios
 */
abstract class AbstractScenarioManagersController extends BackController
{
    /**
     * @return ScenariosManagerPDO
     */
    public function getScenariosManager()
    {
        $managers = [
            'sequencesManagerPDO' => $this->getSequencesManager(),
            'scenarioSequenceManagerPDO' => $this->getScenarioSequenceManager()
        ];

        return $this->managers->getManagerOf(
            'Scenario\Scenarios',
            $managers
        );
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getSequencesManager()
    {
        $managers = [
            'actionManagerPDO' => $this->getActionManager(),
            'sequenceActionManagerPDO' => $this->getSequenceActionManager()
        ];
        return $this->managers->getManagerOf('Scenario\Sequences', $managers);
    }

    /**
     * @return ActionManagerPDO
     */
    public function getActionManager()
    {
        $managers = ['actionneursManagerPDO' => $this->getActionneursManager()];
        return $this->managers->getManagerOf('Scenario\Action', $managers);
    }

    /**
     * @return ActionneursManagerPDO
     */
    public function getActionneursManager()
    {
        return $this->managers->getManagerOf('Actionneurs');
    }

    /**
     * @return SequenceActionManagerPDO
     */
    public function getSequenceActionManager()
    {
        return $this->managers->getManagerOf('Scenario\SequenceAction');
    }

    /**
     * @return ScenarioSequenceManagerPDO
     */
    public function getScenarioSequenceManager()
    {
        return $this->managers->getManagerOf('Scenario\ScenarioSequence');
    }
}
