<?php

namespace Tests\e2e\Thermostat;

use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;
use Exception;
use SFram\Utils;
use OCFram\Managers;
use GuzzleHttp\Client;
use OCFram\PDOFactory;
use Tests\e2e\AbstractEndpointTest;
use Tests\e2e\Thermostat\mock\ThermostatPlanifMock;

/**
 * Class ThermostatPlanifEndpointTest
 * @package Tests\e2e\Thermostat
 */
class ThermostatPlanifEndpointTest extends AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \Model\ThermostatPlanifManagerPDO */
    protected static $thermostatPlanifManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        self::$thermostatPlanifManager = $managers->getManagerOf('ThermostatPlanif');
    }

    /**
     * Route : /thermostat/planif/update
     * 
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdate()
    {
        self::$thermostatPlanifManager->deleteAll();
        $lastId = self::$thermostatPlanifManager->save(ThermostatPlanifMock::getThermostatPlanif());
        self::assertTrue((bool) $lastId);
        $day = 1;
        $thermostatPlanif = self::$thermostatPlanifManager->getByNomIdAndDay($lastId, $day);
        self::assertTrue((bool)$thermostatPlanif->getId());
        list($client, $url) = $this->prepareThermostatPlanifRequest('/thermostat/planif/update');

        $expectedThermostatPlanif  = Utils::deepCopy($thermostatPlanif);
        $expectedThermostatPlanif->setTimetable(ThermostatPlanifMock::getUpdatedTimetable());
        $payload = Utils::objToArray($expectedThermostatPlanif);
        unset($payload['nom']);

        $result = $this->putRequest($client, $url, $payload);
        self::assertEquals(json_encode($expectedThermostatPlanif), $result);
    }

    /**
     * Route : /thermostat/planif/$nomid/$day
     * @depends testUpdate
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testGetByNomIdAndDay()
    {
        $thermostatPlanifNom = self::$thermostatPlanifManager->getNom(
            ThermostatPlanifMock::getThermostatPlanif()->getNom()->getNom(),
            'nom'
        );
        self::assertTrue((bool) $thermostatPlanifNom->getId());
        $day = 1;
        $nomid = $thermostatPlanifNom->getId();
        $thermostatPlanif = self::$thermostatPlanifManager->getByNomIdAndDay($nomid, $day);
        self::assertTrue((bool)$thermostatPlanif->getId());
        list($client, $url) = $this->prepareThermostatPlanifRequest("/thermostat/planif/$nomid/$day");

        $result = $this->getRequest($client, $url);
        self::assertEquals(json_encode($thermostatPlanif), $result);
    }

    /**
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function prepareThermostatPlanifRequest(string $url): array
    {
        $client = new Client();
        $url = $this->getFullUrl($url);

        return [$client, $url];
    }
}
