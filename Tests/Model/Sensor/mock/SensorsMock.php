<?php

namespace Tests\Model\Sensor\mock;

use Entity\Sensor;

/**
 * Class SensorsMock
 * @package Tests\Model\Sensor\mock
 */
class SensorsMock
{
    /**
     * @return \Entity\Sensor[]
     */
    public static function getSensors()
    {
        return [
            new Sensor([
                'radioid' => 'sensor24ctn10id1',
                'releve' => '2020-08-20 05:16:38',
                'actif' => true,
                'valeur1' => 12.4,
                'valeur2' => 0.0,
                'nom' => 'SensorTest1',
                'categorie' => 'thermo',
                'radioaddress' => '4Node'
            ]),
            new Sensor([
                'radioid' => 'sensor43dht22id1',
                'releve' => '2019-08-20 10:16:15',
                'actif' => false,
                'valeur1' => 22.1,
                'valeur2' => 54.2,
                'nom' => 'SensorTest2',
                'categorie' => 'thermo',
                'radioaddress' => '1'
            ]),
            new Sensor([
                'radioid' => 'sensor24ctn10id3',
                'releve' => '2020-08-23 23:58:27',
                'actif' => true,
                'valeur1' => 21.37,
                'valeur2' => 0.0,
                'nom' => 'SensorTest3',
                'categorie' => 'thermo',
                'radioaddress' => '3Node'
            ]),
            new Sensor([
                'radioid' => 'sensor24thermid1',
                'releve' => '2020-08-23 23:58:00',
                'actif' => true,
                'valeur1' => 26.4,
                'valeur2' => 40.5,
                'nom' => 'SensorTest4',
                'categorie' => 'thermostat',
                'radioaddress' => '1Mast'
            ]),
            new Sensor([
                'radioid' => 'sensor24ctn10id4',
                'releve' => '2020-08-23 23:57:58',
                'actif' => true,
                'valeur1' => 26.46,
                'valeur2' => 0.0,
                'nom' => 'SensorTest5',
                'categorie' => 'thermo',
                'radioaddress' => '2Mast'
            ]),
        ];
    }
}