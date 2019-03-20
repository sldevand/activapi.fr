<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Action;
use Entity\Scenario\Scenario;
use Entity\Scenario\Sequence;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
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
}
