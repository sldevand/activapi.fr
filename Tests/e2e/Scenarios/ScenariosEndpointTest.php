<?php

namespace Tests\e2e\Scenarios;

use GuzzleHttp\Client;
use Model\Scenario\ScenarioManagerPDOFactory;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\AbstractEndpointTest;
use Tests\e2e\Scenarios\mock\ScenariosMock;

/**
 * Class ScenariosEndpointTest
 * @package Tests\e2e\Scenarios
 */
class ScenariosEndpointTest extends AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \Model\Scenario\ScenariosManagerPDO */
    protected static $scenariosManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        $scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
        self::$scenariosManager = $scenarioManagerPDOFactory->getScenariosManager();
    }

    /**
     * Route : /scenarios/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithWrongMethod()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios/add');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'Wrong method : GET, use POST method instead'], $result);
    }

    /**
     * Route : /scenarios/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithEmptyBody()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios/add');

        $result = $this->getPostJsonBody($client, $url, []);
        self::assertEquals(['error' => 'No JSON body sent from client'], $result);
    }

    /**
     * Route : /scenarios/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePost()
    {
        list($client, $url, $scenario, $scenarioArray) = $this->prepareScenarioRequest('/scenarios/add');
        $result = $this->getPostJsonBody($client, $url, $scenarioArray);

        $expectedScenario = Utils::deepCopy($scenario);
        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        $expectedScenario->setId($scenarioId);
        $expected = Utils::objToArray($expectedScenario);

        self::assertEquals($expected, $result);

        // We try to insert the same scenario a message error must appear because UNIQUE constraint on nom field
        $result = $this->getPostJsonBody($client, $url, $scenarioArray);

        self::assertEquals(
            ['error' => 'SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: scenario.nom'],
            $result
        );
    }

    /**
     * Route : /scenarios/update
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePut()
    {
        list($client, $url, $scenario, $scenarioArray) = $this->prepareScenarioRequest('/scenarios/update');
        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        $scenario->setId($scenarioId);
        $scenarioArray['id'] = $scenarioId;
        $scenarioArray['nom'] = 'Test2';

        $result = $this->getPutJsonBody($client, $url, $scenarioArray);

        $expectedScenario = Utils::deepCopy($scenario);
        $expectedScenario->setId($scenarioId);
        $expectedScenario->setNom('Test2');
        $expected = Utils::objToArray($expectedScenario);

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /scenarios/update
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithWrongMethod()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios/update');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'Wrong method : GET, use PUT method instead'], $result);
    }

    /**
     * Route : /scenarios/update
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithEmptyBody()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios/update');

        $result = $this->getPutJsonBody($client, $url, []);
        self::assertEquals(['error' => 'No JSON body sent from client'], $result);
    }

    /**
     * Route : /scenarios/update
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithoutId()
    {
        list($client, $url, $scenario, $scenarioArray) = $this->prepareScenarioRequest('/scenarios/update');
        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        $scenario->setId($scenarioId);
        $scenarioArray['nom'] = 'Test2';

        $result = $this->getPutJsonBody($client, $url, $scenarioArray);

        self::assertEquals(['error' => 'JSON body must contain an id'], $result);
    }

    /**
     * Route : /scenarios/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllScenariosWithWrongMethod()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios');
        $result = $this->getPostJsonBody($client, $url, []);

        self::assertEquals(['error' => 'Wrong method : POST, use GET method instead'], $result);
    }

    /**
     * Route : /scenarios/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllScenarios()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios');
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$scenariosManager->getAll());

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /scenarios/?([0-9]*)?
     * @depends testExecuteGetAllScenarios
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUniqueScenario()
    {
        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        list($client, $url) = $this->prepareScenarioRequest("/scenarios/$scenarioId");
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$scenariosManager->getUnique($scenarioId));

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /scenarios/?([0-9]*)?
     * @depends testExecuteGetUniqueScenario
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUndefinedScenario()
    {
        $scenarioId = '569997774';
        list($client, $url) = $this->prepareScenarioRequest("/scenarios/$scenarioId");
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['error' => 'No scenario was found!'], $result);
    }

    /**
     * Route : /scenarios/delete
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteScenarioWithWrongMethod()
    {
        list($client, $url) = $this->prepareScenarioRequest('/scenarios/delete');
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['error' => 'Wrong method : GET, use DELETE method instead'], $result);
    }

    /**
     * Route : /scenarios/delete
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteScenario()
    {
        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        list($client, $url, , $scenarioArray) = $this->prepareScenarioRequest('/scenarios/delete');
        $scenarioArray['id'] = $scenarioId;
        $result = $this->getDeleteJsonBody($client, $url, $scenarioArray);

        self::assertEquals(['success' => 'Test2 has been deleted'], $result);
    }

    /**
     * Route : /scenarios/?([0-9]*)?
     * @depends testExecuteGetUniqueScenario
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteUndefinedScenario()
    {
        $scenarioId = '569997774';
        list($client, $url, , $scenarioArray) = $this->prepareScenarioRequest("/scenarios/delete");
        $scenarioArray['id'] = $scenarioId;
        $result = $this->getDeleteJsonBody($client, $url, $scenarioArray);

        self::assertEquals(['error' => 'No scenario was found!'], $result);
    }

    /**
     * Route : /scenarios/reset
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testExecuteReset()
    {
        list($client, $url) = $this->prepareScenarioRequest("/scenarios/reset");
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['success' => 'Scenarios have been reset to stop value'], $result);
    }

    /**
     * Route : /scenarios/command/([1-9]|[1-9][0-9]*)/?
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function testExecuteCommand()
    {
        list($client, $url, , $scenarioArray) = $this->prepareScenarioRequest('/scenarios/add');
        $this->getPostJsonBody($client, $url, $scenarioArray);

        $scenarioId = self::$scenariosManager->getLastInserted('scenario');
        self::assertNotEmpty($scenarioId);

        list($client, $url) = $this->prepareScenarioRequest("/scenarios/command/$scenarioId");
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['message' => 'Ok'], $result);
    }

    /**
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function prepareScenarioRequest(string $url): array
    {
        $client = new Client();
        $url = $this->getFullUrl($url);

        // Prepare Scenario array
        /** @var  \Entity\Scenario\Scenario $scenario */
        $scenario = Utils::deepCopy(ScenariosMock::getScenarios()[0]);
        $scenarioArray = Utils::objToArray($scenario);

        return [$client, $url, $scenario, $scenarioArray];
    }

    /**
     * @throws \Exception
     */
    public static function removeLastInsertedScenario()
    {
        $id = self::$scenariosManager->getLastInserted('scenario');
        self::$scenariosManager->delete($id);
    }

    /**
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        self::removeLastInsertedScenario();
    }
}
