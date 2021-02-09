<?php

namespace Tests\e2e\Thermostat;

use Entity\Sensor;
use Entity\Thermostat;
use Entity\ThermostatMode;
use GuzzleHttp\Client;
use Model\Scenario\ScenarioManagerPDOFactory;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\AbstractEndpointTest;
use Tests\e2e\Scenarios\mock\SequencesMock;
use Tests\e2e\Thermostat\mock\ThermostatMock;

/**
 * Class ThermostatEndpointTest
 * @package Tests\e2e\Thermostat
 */
class ThermostatEndpointTest extends AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \Model\ThermostatManagerPDO */
    protected static $thermostatManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        self::$thermostatManager = $managers->getManagerOf('Thermostat');
    }

    /**
     * Route : /thermostat/?
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testIndex()
    {
        list($client, $url, $thermostat, $thermostatArray) = $this->prepareThermostatRequest('/thermostat');

        $result = self::$thermostatManager
            ->save($thermostat, ['mode', 'sensor', 'planningName', 'temperature', 'hygrometrie']);

        self::assertTrue($result);

        $thermostatArray['planningName'] = false;
        $thermostatArray['mode'] = [
            'id' => '1',
            'nom' => 'Confort',
            'consigne' => '21.5',
            'delta' => '0.7'
        ];
        $expected = [
            $thermostatArray
        ];

        $responseBody = $this->getJsonBody($client, $url);
        self::assertEquals($expected, $responseBody);
    }

    /**
     * Route : /thermostat/update
     * @depends testIndex
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdate()
    {
        list($client, $url, , $thermostatArray) = $this->prepareThermostatRequest('/thermostat/update');

        /* Trying to update with same object*/
        $result = $this->postRequest($client, $url, $thermostatArray);
        self::assertEquals('No need to log : same thermostats', $result);

        $thermostatToUpdate = Utils::deepCopy(ThermostatMock::getThermostats('update'));
        $thermostatArrayToUpdate = Utils::objToArray($thermostatToUpdate);

        /* Trying to update with another object*/
        $result = $this->postRequest($client, $url, $thermostatArrayToUpdate);
        self::assertEquals('Success', $result);

        /* Trying to update with missing param */
        unset($thermostatArrayToUpdate['interne']);
        $result = $this->postRequest($client, $url, $thermostatArrayToUpdate);
        self::assertEquals('Update Error : Value interne is null!', $result);
    }

    /**
     * Route : /thermostat/log/refresh
     * @depends testIndex
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testRefreshLog()
    {
        list($client, $url) = $this->prepareThermostatRequest('/thermostat/log/refresh');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['message' => 'Success'], $result);
    }

    /**
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function prepareThermostatRequest(string $url): array
    {
        $client = new Client();
        $url = $this->getFullUrl($url);
        $thermostat = Utils::deepCopy(ThermostatMock::getThermostats('create'));
        $thermostatArray = Utils::objToArray($thermostat);

        return [$client, $url, $thermostat, $thermostatArray];
    }
}
