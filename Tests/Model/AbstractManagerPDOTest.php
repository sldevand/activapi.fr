<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Action;
use Entity\Scenario\Scenario;
use Entity\Scenario\ScenarioSequence;
use Entity\Scenario\Sequence;
use Entity\Scenario\SequenceAction;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;

/**
 * Class AbstractManagerPDOTest
 * @package Tests\Model
 */
abstract class AbstractManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    /**
     * @param $nom
     * @param Sequence[] $sequences
     * @param null | int $id
     * @return Scenario
     */
    public function makeScenario($nom, $sequences, $id = null)
    {
        return new Scenario(
            [
                'id' => $id,
                'nom' => $nom,
                'asequences' => $sequences
            ]
        );
    }

    /**
     * @param int $scenarioId
     * @param int $sequenceId
     * @param int [ null $id
     * @return ScenarioSequence
     */
    public function makeScenarioSequence($scenarioId, $sequenceId, $id = null)
    {
        return new ScenarioSequence(
            [
                'id' => $id,
                'scenarioId' => $scenarioId,
                'sequenceId' => $sequenceId
            ]
        );
    }

    /**
     * @param string $nom
     * @param Action[] $actions
     * @param int | null $id
     * @return Sequence
     */
    public function makeSequence($nom, $actions, $id = null)
    {
        return new Sequence(
            [
                'id' => $id,
                'nom' => $nom,
                'actions' => $actions
            ]
        );
    }

    /**
     * @param int $sequenceId
     * @param int $actionId
     * @param int | null $id
     * @return SequenceAction
     */
    public function makeSequenceAction($sequenceId, $actionId, $id = null)
    {
        return new SequenceAction(
            [
                'id' => $id,
                'sequenceId' => $sequenceId,
                'actionId' => $actionId,
            ]
        );
    }

    /**
     * @param Actionneur $actionneur
     * @param int $etat
     * @param int | null $id
     * @return Action
     */
    public function makeAction($actionneur, $etat, $id = null)
    {
        return new Action(
            [
                'id' => $id,
                'actionneur' => $actionneur,
                'etat' => $etat
            ]
        );
    }

    /**
     * @param string $nom
     * @param int | null $id
     * @return Actionneur
     */
    public function makeActionneur($nom, $id = null)
    {
        return new Actionneur(
            [
                'id' => $id,
                'nom' => $nom
            ]
        );
    }

    /**
     * @return ActionManagerPDO
     */
    public function getActionManager()
    {
        $actionneursManagerPDO = self::$managers->getManagerOf('Actionneurs');
        $actionneursManagerPDOArray = ['actionneursManagerPDO' => $actionneursManagerPDO];
        return self::$managers->getManagerOf('Scenario\Action', $actionneursManagerPDOArray);
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getSequencesManager()
    {
        $actionManagerPDOArray = ['actionManagerPDO' => $this->getActionManager()];
        return self::$managers->getManagerOf('Scenario\Sequences', $actionManagerPDOArray);
    }


    /**
     * @return ScenariosManagerPDO
     */
    public function getScenariosManager()
    {
        $sequencesManagerPDOArray = ['sequencesManagerPDO' => $this->getSequencesManager()];
        return self::$managers->getManagerOf('Scenario\Scenarios', $sequencesManagerPDOArray);
    }

    /**
     * @return ScenarioSequenceManagerPDO
     */
    public function getScenarioSequenceManager()
    {
        return self::$managers->getManagerOf('Scenario\ScenarioSequence');
    }

    /**
     * @return SequenceActionManagerPDO
     */
    public function getSequenceActionManager()
    {
        return self::$managers->getManagerOf('Scenario\SequenceAction');
    }
}
