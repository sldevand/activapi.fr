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
                "timetable" => json_encode(['300-1', '600-2','800-1','1200-3']),
                'nomid' => '1',
                'nom' => self::getThermostatPlanifNom()
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
                    "timetable" => json_encode(['300-1', '600-2','800-1','1200-3']),
                    "nomid" => '1',
                    'id' => $day
                ]
            );
        }

        return $thermostatPlanifs;
    }
}