<?php

namespace Tests\e2e\Scenarios\mock;

use Entity\Scenario\Action;
use Tests\Model\Actionneurs\mock\ActionneursMock;

/**
 * Class ActionsMock
 * @package Tests\e2e\Scenarios\mock
 */
class ActionsMock
{
    public static function getActions()
    {

        return [
            new Action(
                [
                    'nom' => 'ActionTest1',
                    'actionneurId' => 1,
                    'etat' => 1,
                    'timeout' => 0
                ]
            ),
            new Action(
                [
                    'nom' => 'ActionTest2',
                    'actionneurId' => 2,
                    'etat' => 0,
                    'timeout' => 1000
                ]
            )
        ];
    }
}