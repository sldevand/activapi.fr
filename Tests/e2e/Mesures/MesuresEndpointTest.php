<?php

namespace Tests\e2e\Mesures;

use Entity\Mesure;
use GuzzleHttp\Client;
use OCFram\DateFactory;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Utils;

/**
 * Class MesuresEndpointTest
 * @package Tests\e2e\Mesures
 */
class MesuresEndpointTest extends \Tests\e2e\AbstractEndpointTest
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
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        self::$db = PDOFactory::getSqliteConnexion();
        self::$managers = new Managers('PDO', self::$db);
        self::$sensorsManager = self::$managers->getManagerOf('Sensors');
        self::$mesuresManager = self::$managers->getManagerOf('Mesures');
    }

    /**
     * Route : /mesures/add-(sensor[0-9]{2}(?:ctn10|dht11|dht22|tinfo|therm|cdoor)id[0-9])-([-+]?[0-9]*\.?[0-9]*)-?([-+]?[0-9]*\.?[0-9]*)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteInsertWithGoodRadioid()
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
     * Route : /mesures/add-(sensor[0-9]{2}(?:ctn10|dht11|dht22|tinfo|therm|cdoor)id[0-9])-([-+]?[0-9]*\.?[0-9]*)-?([-+]?[0-9]*\.?[0-9]*)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteInsertWithUnknownRadioid()
    {
        $radioId = 'sensor24ctn10id8';
        $url = $this->getFullUrl("/mesures/add-$radioId-12-0.0");
        $client = new Client();
        $body = $this->getJsonBody($client, $url);

        self::assertEquals("No entity found with id $radioId", $body);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testExecuteInsertWithBadRadioIdRegexp()
    {
        $radioId = 'badRegexRadioId';
        $url = $this->getFullUrl("/mesures/add-$radioId-12-0.0");
        $client = new Client();

        self::expectException(\GuzzleHttp\Exception\ClientException::class);
        $this->getRequest($client, $url);
    }

    /**
     * Route : /mesures/addchacondio-([0-9]{8}(?:%20[0-9])?)-([-+]?[0-9]*\.?[0-9]*)-?([-+]?[0-9]*\.?[0-9]*)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteInsertChacon()
    {
        $radioid = 'sensor43cdoorid1';
        $sensorBefore = $this->setSensorForTest($radioid);
        $radioaddess = $sensorBefore->radioaddress();

        $client = new Client();

        // first time insert is successful
        $expected = Utils::objToArray(new Mesure(
            [
                'id_sensor' => $radioid,
                'temperature' => '0',
                'hygrometrie' => '0'
            ]
        ));

        $url = $this->getFullUrl("/mesures/addchacondio-$radioaddess-0-0");
        $body = $this->getJsonBody($client, $url);
        self::assertEquals($expected, $body);
        $this->removeLastInsertedMeasure();

        // we change values, the measure can be inserted
        $expected = Utils::objToArray(new Mesure(
            [
                'id_sensor' => $radioid,
                'temperature' => '1',
                'hygrometrie' => '0'
            ]
        ));

        $url = $this->getFullUrl("/mesures/addchacondio-$radioaddess-1-0");
        $body = $this->getJsonBody($client, $url);
        self::assertEquals($expected, $body);
        $this->removeLastInsertedMeasure();

        self::$sensorsManager->save($sensorBefore);
    }

    /**
     * Route : /mesures/(sensor[0-9]{2}(?:ctn10|dht11|dht22|tinfo|therm|cdoor)id[0-9])-(today|yesterday|week|month)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteSensorToday()
    {
        $radioid = 'sensor24ctn10id3';
        $sensorBefore = $this->setSensorForTest($radioid);

        $url = $this->getFullUrl("/mesures/add-$radioid-12-0.0");
        $client = new Client();
        $this->getRequest($client, $url);

        $url = $this->getFullUrl("/mesures/$radioid-today");
        $body = $this->getJsonBody($client, $url);

        $dates = DateFactory::getDateLimits("today");
        $dateMinFull = $dates['dateMin'] . " 00:00:00";
        $dateMaxFull = $dates['dateMax'] . " 23:59:59";

        $mesures = self::$mesuresManager->getSensorList($sensorBefore->radioid(), $dateMinFull, $dateMaxFull);

        $expected = Utils::objToArray([
            'nom' => $sensorBefore->nom(),
            'sensor_id' => $sensorBefore->radioid(),
            'id' => $sensorBefore->id(),
            'data' => $mesures
        ]);

        self::assertEquals($expected, $body);

        self::$sensorsManager->save($sensorBefore);
    }

    /**
     * Route : /mesures/get-(sensor[0-9]{2}(?:ctn10|dht11|dht22|tinfo|therm|cdoor)id[0-9])
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteSensorStruct()
    {
        $radioid = 'sensor24ctn10id3';

        $client = new Client();
        $url = $this->getFullUrl("/mesures/get-$radioid");
        $body = json_decode($this->getRequest($client, $url, 8192), true);
        $sensor = self::$mesuresManager->getSensor($radioid);
        $expectedSensor = Utils::objToArray($sensor);
        $expected = [$expectedSensor];

        self::assertEquals($expected, $body);
    }


    /**
     * Route : /mesures/get-sensors(?:/)?(thermo|thermostat|teleinfo)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteSensorsWithNoCategorieFilter()
    {
        $client = new Client();
        $url = $this->getFullUrl("/mesures/get-sensors");
        $body = json_decode($this->getRequest($client, $url, 8192), true);
        $sensors = self::$sensorsManager->getList();
        $expected = Utils::objToArray($sensors);

        self::assertEquals($expected, $body);
    }



    /**
     * Route : /mesures/get-sensors(?:/)?(thermo|thermostat|teleinfo)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteSensorsWithCategorieFilter()
    {
        $client = new Client();
        $url = $this->getFullUrl("/mesures/get-sensors/thermo");
        $body = json_decode($this->getRequest($client, $url, 8192), true);
        $sensors = self::$sensorsManager->getList('thermo');
        $expected = Utils::objToArray($sensors);

        self::assertEquals($expected, $body);
    }

    /**
     * @param string $radioId
     * @return \Entity\Sensor
     * @throws \Exception
     */
    protected function setSensorForTest(string $radioId)
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
     * @throws \Exception
     */
    public function removeLastInsertedMeasure()
    {
        $id = self::$mesuresManager->getLastInserted('mesures');
        $rows = self::$mesuresManager->delete($id);
        self::assertEquals(1, $rows);
    }
}
