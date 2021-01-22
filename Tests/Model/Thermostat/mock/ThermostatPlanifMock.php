<?php

namespace Tests\Model\Thermostat\mock;

use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;
use OCFram\DateFactory;

/**
 * Class ThermostatModesMock
 * @package Tests\Model\Thermostat\mock
 */
class ThermostatPlanifMock
{
    protected static $thermostatPlanifs;

    /**
     * @return \Entity\ThermostatPlanif[]
     */
    public static function getThermostatPlanif()
    {
        if (!isset(self::$thermostatPlanifs)) {
            $modes = ThermostatModesMock::getThermostatModes();

            $thermostatPlanifsCommon = [
                'jour' => 1,
                'modeid' => 1,
                'defaultModeid' => 3,
                'heure1Start' => '',
                'heure1Stop' => '',
                'heure2Start' => '',
                'heure2Stop' => '',
                'nomid' => '1',
                'nom' => self::getThermostatPlanifNom(),
                'mode' => $modes[0],
                'defaultMode' => $modes[1]
            ];

            $thermostatPlanifs = [];
            $days = DateFactory::$days;
            foreach ($days as $dayNumber => $day) {
                $thermostatPlanifsCommon ['jour'] = $dayNumber;
                $thermostatPlanifs[] = new ThermostatPlanif($thermostatPlanifsCommon);
            }

            self::$thermostatPlanifs = $thermostatPlanifs;
        }

        return self::$thermostatPlanifs;
    }

    public static function getThermostatPlanifNom() {
        return new ThermostatPlanifNom(['nom' => 'Test']);
    }

    /**
     * @return mixed
     */
    public static function getDefaultThermostatPlanifs() {
        for ($day = 1; $day < 8; $day++) {
            $thermostatPlanifs[] = new ThermostatPlanif(
                [
                    "jour" => $day,
                    "modeid" => "1",
                    "defaultModeid" => "3",
                    "heure1Start" => "07:00",
                    "heure1Stop" => "23:00",
                    "heure2Start" => "",
                    "heure2Stop" => "",
                    "nomid" => '1',
                    'id' => $day
                ]
            );
        }

        return $thermostatPlanifs;
    }
}