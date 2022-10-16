<?php

namespace Tests\e2e\Thermostat\mock;

use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;

/**
 * Class ThermostatPlanifMock
 * @package Tests\e2e\Thermostat\mock
 */
class ThermostatPlanifMock
{
    /**
     * @return string
     */
    public static function getUpdatedTimetable(): string
    {
        return json_encode(["400-1", "600-2", "900-3", "1100-4"]) ?: '';
    }

    /**
     * @return \Entity\ThermostatPlanif
     */
    public static function getThermostatPlanif(): ThermostatPlanif
    {
        $thermostaPlanifNom = new ThermostatPlanifNom(['nom' => 'Test']);
        return new ThermostatPlanif(['nom' => $thermostaPlanifNom]);
    }
}
