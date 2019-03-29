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
     * Saves four actions
     *
     * @throws \Exception
     */
    public function fixtureActions()
    {
        $actionneurManager = $this->getActionneursManager();
        $actionsManager = $this->getActionManager();
        $actionneurs = $actionneurManager->getAll();
        $action = $this->makeAction('Test1', $actionneurs[0], 120);
        $action2 = $this->makeAction('Test2', $actionneurs[1], 255);
        $action3 = $this->makeAction('Test3', $actionneurs[2], 0);
        $action4 = $this->makeAction('Test4', $actionneurs[3], 12);
        $actionsManager->save($action);
        $actionsManager->save($action2);
        $actionsManager->save($action3);
        $actionsManager->save($action4);
    }

    /**
     * Saves two sequences
     *
     * @throws \Exception
     */
    public function fixtureSequences()
    {
        $sequencesManager = $this->getSequencesManager();
        $actionsManager = $this->getActionManager();
        $actions = $actionsManager->getAll();

        $actionsForSequence1 = array_slice($actions, 0, 2);
        $actionsForSequence2 = array_slice($actions, 2);

        $sequence1 = $this->makeSequence('Test1', $actionsForSequence1);
        $sequence2 = $this->makeSequence('Test2', $actionsForSequence2);

        $sequencesManager->save($sequence1);
        $sequencesManager->save($sequence2);
    }

    /**
     * return Action[]
     */
    public function mockActions()
    {
        $actionneurs = $this->mockActionneurs();

        return [
            $this->makeAction('Test1', $actionneurs[0], 120, 1),
            $this->makeAction('Test2', $actionneurs[1], 255, 2),
            $this->makeAction('Test3', $actionneurs[2], 0, 3),
            $this->makeAction('Test4', $actionneurs[3], 12, 4)
        ];
    }

    /**
     * @return Sequence[]
     */
    public function mockSequences()
    {
        $actions = $this->mockActions();

        $actionsForSequence1 = array_slice($actions, 0, 2);
        $actionsForSequence2 = array_slice($actions, 2);

        return [
            $this->makeSequence('Test1', $actionsForSequence1, 1),
            $this->makeSequence('Test2', $actionsForSequence2, 2)
        ];
    }


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
     * @param $nom
     * @param Actionneur $actionneur
     * @param int $etat
     * @param int | null $id
     * @return Action
     */
    public function makeAction($nom, $actionneur, $etat, $id = null)
    {
        return new Action(
            [
                'id' => $id,
                'nom' => $nom,
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
     * @return Actionneur[]
     */
    public function mockActionneurs()
    {
        /** @var Actionneur $actionneur */
        return [
            new Actionneur(
                [
                    'id' => 1,
                    'nom' => 'Salon',
                    'module' => 'cc1101',
                    'protocole' => 'chacon',
                    'adresse' => '14549858',
                    'type' => 'relay',
                    'radioid' => 2,
                    'etat' => 0,
                    'categorie' => 'inter'
                ]
            ),
            new Actionneur(
                [
                    'id' => 2,
                    'nom' => 'Dalle_TV',
                    'module' => 'bt',
                    'protocole' => 'cnt',
                    'adresse' => '00:00:00:00:00',
                    'type' => 'blueLamp',
                    'radioid' => 'val',
                    'etat' => 210,
                    'categorie' => 'dimmer'
                ]
            ),
            new Actionneur(
                [
                    'id' => 3,
                    'nom' => 'Thermostat',
                    'module' => 'nrf24',
                    'protocole' => 'node',
                    'adresse' => '2Nodw',
                    'type' => 'ther',
                    'radioid' => '0',
                    'etat' => 0,
                    'categorie' => 'thermostat'
                ]
            ),
            new Actionneur(
                [
                    'id' => 4,
                    'nom' => 'Chambre',
                    'module' => 'cc1101',
                    'protocole' => 'chacon',
                    'adresse' => '14549858',
                    'type' => 'relay',
                    'radioid' => '1',
                    'etat' => 0,
                    'categorie' => 'inter'
                ]
            )
        ];
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
