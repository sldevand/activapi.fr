<?php

namespace Tests\Model\Actionneurs\mock;

use Entity\Actionneur;

/**
 * Class ActionneursMock
 * @package Tests\Model\Actionneurs\mock
 */
class ActionneursMock
{
    /**
     * @return \Entity\Actionneur[]
     */
    public static function getActionneurs()
    {
        return [
            new Actionneur(
                [
                    'nom' => 'Salon',
                    'module' => 'cc1101',
                    'protocole' => 'chacon',
                    'adresse' => '65988788',
                    'type' => 'relay',
                    'radioid' => '2',
                    'etat' => '0',
                    'categorie' => 'inter'
                ]
            ),
            new Actionneur(
                [
                    'nom' => 'Thermostat',
                    'module' => 'nrf24',
                    'protocole' => 'node',
                    'adresse' => 'dfler4',
                    'type' => 'ther',
                    'radioid' => '0',
                    'etat' => '0',
                    'categorie' => 'thermostat'
                ]
            ),
            new Actionneur(
                [
                    'nom' => 'Chambre',
                    'module' => 'cc1101',
                    'protocole' => 'chacon',
                    'adresse' => '65988788',
                    'type' => 'relay',
                    'radioid' => '1',
                    'etat' => '0',
                    'categorie' => 'inter'
                ]
            )
        ];
    }
}