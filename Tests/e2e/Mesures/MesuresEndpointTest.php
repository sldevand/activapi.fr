<?php

namespace Tests\e2e\Mesures;

use GuzzleHttp\Client;
use Model\MesuresManagerPDO;
use OCFram\DateFactory;
use OCFram\Managers;
use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class MesuresEndpointTest
 * @package Tests\e2e\Mesures
 */
class MesuresEndpointTest extends TestCase
{
    /** @var \PDO */
    protected static $db;

    /** @var \OCFram\Managers */
    protected static $managers;

    /** @var \Model\SensorsManagerPDO */
    protected static $sensorsManager;

    /** @var \Model\MesuresManagerPDO */
    private static $mesuresManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ??  $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        self::$db = PDOFactory::getSqliteConnexion();
        self::$managers = new Managers('PDO', self::$db);
        self::$sensorsManager = self::$managers->getManagerOf('Sensors');
        self::$mesuresManager = self::$managers->getManagerOf('Mesures');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testExecuteInsert()
    {
        $radioid = 'sensor24ctn10id3';
        $sensorBefore = $this->setSensorForTest($radioid);
        $url = $this->getFullUrl("/mesures/add-$radioid-12-0.0");
        $client = new Client();
        $body = $this->getRequest($client, $url);

        // first time insert is successful
        self::assertEquals('true', $body);
        $this->removeLastInsertedMeasure();

        // second time insert fails because values did not change
        self::assertEquals('0', $this->getRequest($client, $url));

        // we change values, the measure can be inserted
        $url = $this->getFullUrl("/mesures/add-$radioid-14.2-0.0");
        self::assertEquals('true', $this->getRequest($client, $url));
        $this->removeLastInsertedMeasure();

        self::$sensorsManager->save($sensorBefore);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testExecuteSensor()
    {
        $radioid = 'sensor24ctn10id3';
        $sensorBefore = $this->setSensorForTest($radioid);

        $url = $this->getFullUrl("/mesures/add-$radioid-12-0.0");
        $client = new Client();
        $this->getRequest($client, $url);

        $url = $this->getFullUrl("/mesures/$radioid-today");
        $body = json_decode($this->getRequest($client, $url, 320000), true);

        $dates = DateFactory::getDateLimits("today");
        $dateMinFull = $dates['dateMin'] . " 00:00:00";
        $dateMaxFull = $dates['dateMax'] . " 23:59:59";

        $mesures = self::$mesuresManager->getSensorList($sensorBefore->radioid(), $dateMinFull, $dateMaxFull);

        $expected = json_decode(json_encode([
            'nom' => $sensorBefore->nom(),
            'sensor_id' => $sensorBefore->radioid(),
            'id' => $sensorBefore->id(),
            'data' => $mesures
        ]),true);

        self::assertEquals($expected, $body);

        self::$sensorsManager->save($sensorBefore);
    }

    protected function setSensorForTest($radioId)
    {
        /** @var \Entity\Sensor $sensorBefore */
        $sensorBefore = self::$sensorsManager->getUniqueBy('radioid', $radioId);

        // we change values to 1 so the new measure can be inserted
        /** @var \Entity\Sensor $sensor */
        $sensor = unserialize(serialize($sensorBefore));
        $sensor->setValeur1('1');
        $sensor->setValeur2('1');
        self::$sensorsManager->save($sensor);

        return $sensorBefore;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getFullUrl(string $path)
    {
        $baseUrl = $_ENV['TEST_BASE_URL'] ?? $_ENV['BASE_URL'];
        $rootApiUri =  $_ENV['TEST_ROOT_API_URI'] ??  $_ENV['ROOT_API_URI'];

        return $baseUrl . $rootApiUri . $path;
    }

    /**
     * @param Client $client
     * @param string $url
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequest(Client $client, string $url, int $length = 10)
    {
        $response = $client->request("GET", $url);

        return $response->getBody()->read($length);
    }

    /**
     * @throws \Exception
     */
    public function removeLastInsertedMeasure()
    {
        $id = self::$mesuresManager->getLastInserted('mesures');
        $rows = self::$mesuresManager->delete($id);
        self::assertEquals(1, $rows);
    }
}