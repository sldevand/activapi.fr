<?php

namespace Tests\Model\Thermostat\mock;

use Entity\ThermostatMode;

/**
 * Class ThermostatModesMock
 * @package Tests\Model\Thermostat\mock
 */
class ThermostatModesMock
{
    /**
     * @return \Entity\ThermostatMode[]
     */
    public static function getThermostatModes()
    {
        return [
            new ThermostatMode(
                [
                    'nom' => 'Confort',
                    'consigne' => 21.5,
                    'delta' => 	0.7
                ]
            ),
            new ThermostatMode(
                [
                    'nom' => 'Eco',
                    'consigne' => 18,
                    'delta' => 1
                ]
            ),
            new ThermostatMode(
                [
                    'nom' => 'Nuit',
                    'consigne' => 19.5,
                    'delta' => 	0.7
                ]
            ),
            new ThermostatMode(
                [
                    'nom' => 'Hors gel',
                    'consigne' => 9,
                    'delta' => 1
                ]
            )
        ];
    }
}