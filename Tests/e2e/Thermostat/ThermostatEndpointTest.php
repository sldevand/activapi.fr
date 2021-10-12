<?php

namespace Tests\e2e\Thermostat;

use Entity\Thermostat;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\AbstractEndpointTest;
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
            ->save($thermostat, ['mode', 'sensor', 'planningName', 'temperature', 'hygrometrie', 'lastTurnOn']);

        self::assertTrue($result);

        $thermostatArray['planningName'] = ['id' => '5', 'nom' => 'Presence'];
        $thermostatArray['mode'] = [
            'id' => '1',
            'nom' => 'Confort',
            'consigne' => '21.5',
            'delta' => '0.7'
        ];
        $thermostatArray['lastTurnOn'] = self::$thermostatManager->getLastTurnOnLog();
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
     * Route : /thermostat/update
     * @depends testUpdate
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testLastPwrOff()
    {
        list($client, $url, ,$thermostatArray) = $this->prepareThermostatRequest('/thermostat/update');

        /* Trying to update with pwr on, nothing happens for lastPwrOff */
        /** @var Thermostat $thermostatBefore */
        $thermostatBefore = current(self::$thermostatManager->getList());
        $this->postRequest($client, $url, $thermostatArray);
        sleep(1);
        /** @var Thermostat $thermostatAfter */
        $thermostatAfter = current(self::$thermostatManager->getList());
        self::assertEquals($thermostatAfter->getLastPwrOff(), $thermostatBefore->getLastPwrOff());

        /* Trying to update with pwr off, lastPwrOff must equals to now */
        $thermostatArray['pwr'] = '0';
        $result = $this->postRequest($client, $url, $thermostatArray);
        sleep(1);
        self::assertEquals('No need to log : same thermostats', $result);

        /** @var Thermostat $thermostatAfterPwrOff */
        $thermostatAfterPwrOff = current(self::$thermostatManager->getList());
        self::assertNotEquals($thermostatAfterPwrOff->getLastPwrOff(), $thermostatAfter->getLastPwrOff());
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

    /**
     * Route : /thermostat/log/
     * @depends testIndex
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testLogWithoutParams()
    {
        list($client, $url) = $this->prepareThermostatRequest('/thermostat/log/');

        $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $dateMinFull = $date->format("Y-m-d 00:00:00");
        $dateMaxFull = $date->format("Y-m-d 23:59:59");
        $logList = self::$thermostatManager->getLogListWithDates($dateMinFull, $dateMaxFull);
        $logList = Utils::objToArray($logList);

        $expected = [
            'id' => 1,
            'nom' => 'Thermostat',
            'sensor_id' => 'ther',
            'data' => $logList
        ];

        $result = $this->getJsonBody($client, $url);
        self::assertEquals($expected, $result);
    }

    /**
     * Route : /thermostat/log/([0-9]{4}-[0-9]{2}-[0-9]{2})?-?([0-9]{4}-[0-9]{2}-[0-9]{2})?
     * @depends testIndex
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testLogWithParams()
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $threeDaysAgo = $now->sub(\DateInterval::createFromDateString('-3 days'));

        $dateMinParam = $threeDaysAgo->format("Y-m-d");
        $dateMaxParam = $now->format("Y-m-d");

        list($client, $url) = $this->prepareThermostatRequest("/thermostat/log/$dateMinParam-$dateMaxParam");

        $dateMinFull = $threeDaysAgo->format("Y-m-d 00:00:00");
        $dateMaxFull = $now->format("Y-m-d 23:59:59");
        $logList = self::$thermostatManager->getLogListWithDates($dateMinFull, $dateMaxFull);
        $logList = Utils::objToArray($logList);

        $expected = [
            'id' => 1,
            'nom' => 'Thermostat',
            'sensor_id' => 'ther',
            'data' => $logList
        ];

        $result = $this->getJsonBody($client, $url);
        self::assertEquals($expected, $result);
    }

    /**
     * Route : /thermostat/log/([0-9]{4}-[0-9]{2}-[0-9]{2})?-?([0-9]{4}-[0-9]{2}-[0-9]{2})?
     * @depends testIndex
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testLogWithWrongParams()
    {
        list($client, $url) = $this->prepareThermostatRequest("/thermostat/log/4ed122-45542");

        self::expectException(GuzzleException::class);

        $this->getJsonBody($client, $url);
    }
}
