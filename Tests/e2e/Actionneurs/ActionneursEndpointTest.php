<?php

namespace Tests\e2e\Actionneurs;

use Entity\Actionneur;
use GuzzleHttp\Client;
use OCFram\Managers;
use OCFram\PDOFactory;
use SFram\Utils;

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

        $body = [
            'nom' => 'nomTest',
            'module' => 'moduleTest',
            'protocole' => 'protocoleTest',
            'adresse' => 'protocoleTest',
            'type' => 'typeTest',
            'radioid' => 'radioidTest',
            'etat' => 'etatTest',
            'categorie' => 'categorieTest',
        ];

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

        $body = [
            'nom' => 'nomTest',
            'module' => 'moduleTest',
            'protocole' => 'protocoleTest',
            'adresse' => 'protocoleTest',
            'type' => 'typeTest',
            'radioid' => 'radioidTest',
            'etat' => 'etatTest',
            'categorie' => 'categorieTest',
        ];

        $url = $this->getFullUrl("/actionneurs/add");
        $responseBody = $this->getPostJsonBody($client, $url, $body);

        $expected = ['message' => 'Ok'];

        self::assertEquals($expected, $responseBody);

        /** @var Actionneur $saved */
        $saved = self::$actionneursManager->getUniqueBy('nom', 'nomTest');
        $body['id'] = $saved->id();
        $saved = Utils::objToArray($saved);

        self::assertEquals($body, $saved);

        $body = [
            'id' => $saved['id'],
            'nom' => 'nomTest',
            'module' => 'moduleTest2',
            'protocole' => 'protocoleTest2',
            'adresse' => 'protocoleTest2',
            'type' => 'typeTest2',
            'radioid' => 'radioidTest2',
            'etat' => 'etatTest2',
            'categorie' => 'categorieTest2',
        ];

        $url = $this->getFullUrl("/actionneurs/update");
        $responseBody = $this->getPostJsonBody($client, $url, $body);

        $expected = ['message' => 'Ok'];
        self::assertEquals($expected, $responseBody);

        $updated = Utils::objToArray(self::$actionneursManager->getUniqueBy('nom', 'nomTest'));
        self::assertEquals($body, $updated);

        self::removeLastInsertedActionneur();
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
