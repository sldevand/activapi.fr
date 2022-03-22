<?php

namespace Tests\Model\Thermostat\mock;

use Entity\Thermostat;

/**
 * Class ThermostatMock
 * @package Tests\Model\Thermostat\mock
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