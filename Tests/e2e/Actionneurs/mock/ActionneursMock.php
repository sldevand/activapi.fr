<?php

namespace Tests\e2e\Actionneurs\mock;

use Entity\Actionneur;
use SFram\Utils;

/**
 * Class ActionneursMock
 * @package Tests\e2e\Actionneurs\mock
 */
class ActionneursMock
{
    /**
     * @param null | string $key
     * @return array[]
     * @throws \Exception
     */
    public static function getActionneurs(?string $key = null)
    {
        $actionneurs = [
            'create' => new Actionneur(
                [
                    'nom' => 'nomTest',
                    'module' => 'moduleTest',
                    'protocole' => 'protocoleTest',
                    'adresse' => 'protocoleTest',
                    'type' => 'typeTest',
                    'radioid' => 'radioidTest',
                    'etat' => 'etatTest',
                    'categorie' => 'categorieTest',
                ]
            ),
            'update' => new Actionneur(
                [
                    'nom' => 'nomTest',
                    'module' => 'moduleTest2',
                    'protocole' => 'protocoleTest2',
                    'adresse' => 'protocoleTest2',
                    'type' => 'typeTest2',
                    'radioid' => 'radioidTest2',
                    'etat' => 'etatTest2',
                    'categorie' => 'categorieTest2',
                ]
            )
        ];

        if (is_null($key)) {
            return Utils::objToArray($actionneurs);
        }

        if (empty($actionneurs[$key])) {
            throw new \Exception('The asked object not found');
        }

        return Utils::objToArray($actionneurs[$key]);
    }
}