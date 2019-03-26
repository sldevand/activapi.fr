<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Action;
use Entity\Scenario\Scenario;
use Entity\Scenario\ScenarioSequence;
use Entity\Scenario\Sequence;
use Entity\Scenario\SequenceAction;
use Model\ActionneursManagerPDO;
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
                'sequences' => $sequences
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
     * @return \Entity\Actionneur
     * @throws \Exception
     */
    public function mockActionneur()
    {
        /** @var Actionneur $actionneur */
        return new Actionneur(
            [
                'id' => '1',
                'nom' => 'Salon',
                'module' => 'cc1101',
                'protocole' => 'chacon',
                'adresse' => '14549858',
                'type' => 'relay',
                'radioid' => 2,
                'etat' => 0,
                'categorie' => 'inter'
            ]
        );
    }



    /**
     * @return ActionManagerPDO
     */
    public function getActionManager()
    {
        $managers = ['actionneursManagerPDO' => $this->getActionneursManager()];
        return self::$managers->getManagerOf('Scenario\Action', $managers);
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
        return self::$managers->getManagerOf('Scenario\Sequences', $managers);
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

        return self::$managers->getManagerOf(
            'Scenario\Scenarios',
            $managers
        );
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

    /**
     * @return ActionneursManagerPDO
     */
    public function getActionneursManager()
    {
        return self::$managers->getManagerOf('Actionneurs');
    }
}
