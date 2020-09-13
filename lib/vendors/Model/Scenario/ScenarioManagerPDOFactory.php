<?php

namespace Model\Scenario;

use OCFram\Managers;
use OCFram\PDOFactory;

/**
 * Class ScenarioManagerPDOFactory
 * @package Model\Scenario
 */
class ScenarioManagerPDOFactory
{
    /** @var \OCFram\Managers */
    protected $managers;

    /**
     * ScenarioManagerPDOFactory constructor.
     */
    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
    }

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
     * @return \Model\ActionneursManagerPDO
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
