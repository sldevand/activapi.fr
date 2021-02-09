<?php

namespace Tests\e2e\Scenarios\mock;

use Entity\Scenario\Scenario;

/**
 * Class ScenariosMock
 * @package Tests\e2e\Scenarios\mock
 */
class ScenariosMock
{
    /**
     * @return \Entity\Scenario\Scenario[]
     */
    public static function getScenarios(): array
    {
        return [
            new Scenario(
                [
                    'nom' => 'ScenarioTest1',
                    'status' => '0',
                    'sequences' => []
                ]
            ),
            new Scenario(
                [
                    'nom' => 'ScenarioTest1',
                    'status' => '0',
                    'sequences' => []
                ]
            )
        ];
    }
}