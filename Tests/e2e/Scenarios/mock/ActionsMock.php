<?php

namespace Tests\e2e\Scenarios\mock;

use Entity\Scenario\Action;

/**
 * Class ActionsMock
 * @package Tests\e2e\Scenarios\mock
 */
class ActionsMock
{
    /**
     * @return \Entity\Scenario\Action[]
     */
    public static function getActions(): array
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