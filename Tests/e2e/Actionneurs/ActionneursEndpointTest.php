<?php

namespace Tests\e2e\Actionneurs;

use Entity\Actionneur;
use GuzzleHttp\Client;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\Actionneurs\mock\ActionneursMock;

/**
 * Class ActionneursEndpointTest
 * @package Tests\e2e\Actionneurs
 */
class ActionneursEndpointTest extends \Tests\e2e\AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \OCFram\Managers */
    protected static $managers;

    /** @var \Model\ActionneursManagerPDO */
    protected static $actionneursManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        self::$db = PDOFactory::getSqliteConnexion();
        self::$managers = new Managers('PDO', self::$db);
        self::$actionneursManager = self::$managers->getManagerOf('Actionneurs');
    }

    /**
     * Route : /actionneurs/?(inter|dimmer|multiplug|thermostat)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteIndex()
    {
        $url = $this->getFullUrl("/actionneurs");
        $client = new Client();
        $body = $this->getJsonBody($client, $url);

        $expected = Utils::objToArray(self::$actionneursManager->getList());

        self::assertEquals($expected, $body);
    }


    /**
     * Route : /actionneurs/?(inter|dimmer|multiplug|thermostat)?
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteIndexWithInterFilter()
    {
        $url = $this->getFullUrl("/actionneurs/inter");
        $client = new Client();
        $body = $this->getJsonBody($client, $url);

        $expected = Utils::objToArray(self::$actionneursManager->getList('inter'));

        self::assertEquals($expected, $body);
    }

    /**
     * Route : /actionneurs/add
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteInsertWithoutPostData()
    {
        $url = $this->getFullUrl("/actionneurs/add");
        $client = new Client();
        $body = $this->getJsonBody($client, $url);

        $expected = ['error' => "in object " . Actionneur::class . " , nom is not set"];

        self::assertEquals($expected, $body);
    }

    /**
     * Route : /actionneurs/add
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteInsertWithPostData()
    {
        $url = $this->getFullUrl("/actionneurs/add");
        $client = new Client();

        $body = ActionneursMock::getActionneurs('create');
        $responseBody = $this->getPostJsonBody($client, $url, $body);

        $expected = ['message' => 'Ok'];

        self::assertEquals($expected, $responseBody);

        $saved = self::$actionneursManager->getUniqueBy('nom', 'nomTest');
        $body['id'] = $saved->id();
        $saved = Utils::objToArray($saved);

        self::assertEquals($body, $saved);

        self::removeLastInsertedActionneur();
    }

    /**
     * Route : /actionneurs/update
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteUpdateWithoutPostData()
    {
        $url = $this->getFullUrl("/actionneurs/update");
        $client = new Client();
        $body = $this->getJsonBody($client, $url);

        $expected = ['error' => 'The request must have an id'];

        self::assertEquals($expected, $body);
    }

    /**
     * Route : /actionneurs/update
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteUpdateWithPostData()
    {
        $client = new Client();

        $body = ActionneursMock::getActionneurs('create');
        $url = $this->getFullUrl("/actionneurs/add");
        $responseBody = $this->getPostJsonBody($client, $url, $body);

        $expected = ['message' => 'Ok'];

        self::assertEquals($expected, $responseBody);

        /** @var Actionneur $saved */
        $saved = self::$actionneursManager->getUniqueBy('nom', 'nomTest');
        $body['id'] = $saved->id();
        $saved = Utils::objToArray($saved);

        self::assertEquals($body, $saved);

        $body = ActionneursMock::getActionneurs('update');
        $body['id'] = $saved['id'];

        $url = $this->getFullUrl("/actionneurs/update");
        $responseBody = $this->getPostJsonBody($client, $url, $body);

        $expected = ['message' => 'Ok'];
        self::assertEquals($expected, $responseBody);

        $updated = Utils::objToArray(self::$actionneursManager->getUniqueBy('nom', 'nomTest'));
        self::assertEquals($body, $updated);

        self::removeLastInsertedActionneur();
    }

    /**
     * Route /actionneurs/command/([1-9]|[1-9][0-9]*)/([0-9]|[0-9][0-9]|[0-2][0-5][0-5])/?$
     */
    public function testExecuteCommandWithBadParams()
    {
        $client = new Client();

        $actionneurId = '999';
        $etat = '250';
        $url = $this->getFullUrl("/actionneurs/command/$actionneurId/$etat");
        $responseBody = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'No actionneur on id ' . $actionneurId], $responseBody);
    }

    /**
     * @throws \Exception
     */
    public static function removeLastInsertedActionneur()
    {
        $id = self::$actionneursManager->getLastInserted('actionneurs');
        self::$actionneursManager->delete($id);
    }

    public static function tearDownAfterClass()
    {
        self::removeLastInsertedActionneur();
    }
}
