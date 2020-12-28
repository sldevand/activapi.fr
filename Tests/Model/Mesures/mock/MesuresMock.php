<?php

namespace Tests\Model\Mesures\mock;

use Entity\Mesure;
use Tests\Model\Sensor\mock\SensorsMock;

/**
 * Class MesuresMock
 * @package Tests\Model\Mesures\mock
 * @author Synolia <contact@synolia.com>
 */
class MesuresMock
{
    /**
     * @return \Entity\Mesure[]
     */
    public static function getMesures()
    {
        return [
            new Mesure(
                [
                    'id_sensor' => '1',
                    'temperature' => 10.4,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 10:44:20'
                ]
            ),
            new Mesure(
                [
                    'id_sensor' => '1',
                    'temperature' => 11.5,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 11:50:20'
                ]
            ),
            new Mesure(
                [
                    'id_sensor' => '1',
                    'temperature' => 11.8,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 12:44:20'
                ]
            ),
            new Mesure(
                [
                    'id_sensor' => '1',
                    'temperature' => 12.5,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 13:40:20'
                ]
            ),
            new Mesure(
                [
                    'id_sensor' => '2',
                    'temperature' => 14.5,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 15:44:20'
                ]
            ),
            new Mesure(
                [
                    'id_sensor' => '2',
                    'temperature' => 16.5,
                    'hygrometrie' => 0.0,
                    'horodatage' => '2019-10-22 16:44:20'
                ]
            ),
        ];
    }
}