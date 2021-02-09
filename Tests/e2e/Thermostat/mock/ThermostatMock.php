<?php

namespace Tests\e2e\Thermostat\mock;

use Entity\Thermostat;

/**
 * Class ThermostatMock
 * @package Tests\e2e\Thermostat\mock
 */
class ThermostatMock
{
    /**
     * @param null | string $key
     * @return \Entity\Thermostat|\Entity\Thermostat[]
     * @throws \Exception
     */
    public static function getThermostats(?string $key = null)
    {
        $thermostats = [
            'create' => new Thermostat(
                [
                    'id' => 1,
                    'nom' => 'Thermostat',
                    'modeId' => 1,
                    'sensorId' => 2,
                    'planning' => 5,
                    'manuel' => 1,
                    'consigne' => 21.5,
                    'delta' => 0.7,
                    'interne' => 1,
                    'etat' => 1,
                    'releve' => '2021-01-01 00:00:00',
                    'pwr' => 1
                ]
            ),
            'update' => new Thermostat(
                [
                    'id' => 1,
                    'nom' => 'Thermostat',
                    'modeId' => 1,
                    'sensorId' => 2,
                    'planning' => 5,
                    'manuel' => 0,
                    'consigne' => 22.0,
                    'delta' => 0.7,
                    'interne' => 0,
                    'etat' => 0,
                    'releve' => '2021-01-01 00:00:00',
                    'pwr' => 0
                ]
            )
        ];

        if (is_null($key)) {
            return $thermostats;
        }

        if (empty($thermostats[$key])) {
            throw new \Exception('The asked object not found');
        }

        return $thermostats[$key];
    }
}