<?php

namespace Tests\e2e\Scenarios;

use GuzzleHttp\Client;
use Model\Scenario\ScenarioManagerPDOFactory;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\AbstractEndpointTest;
use Tests\e2e\Scenarios\mock\ActionsMock;

/**
 * Class ActionsEndpointTest
 * @package Tests\e2e\Mesures
 */
class ActionsEndpointTest extends AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \Model\Scenario\ActionManagerPDO */
    protected static $actionManager;

    /** @var \Model\ActionneursManagerPDO */
    protected static $actionneursManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        $scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
        self::$actionManager = $scenarioManagerPDOFactory->getActionManager();
        self::$actionneursManager = $scenarioManagerPDOFactory->getActionneursManager();
    }

    /**
     * Route : /actions/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithWrongMethod()
    {
        list($client, $url) = $this->prepareActionRequest('/actions/add');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'Wrong method : GET, use POST method instead'], $result);
    }

    /**
     * Route : /actions/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithEmptyBody()
    {
        list($client, $url) = $this->prepareActionRequest('/actions/add');

        $result = $this->getPostJsonBody($client, $url, []);
        self::assertEquals(['error' => 'No JSON body sent from client'], $result);
    }

    /**
     * Route : /actions/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePost()
    {
        list($client, $url, $action, $actionArray) = $this->prepareActionRequest('/actions/add');
        $result = $this->getPostJsonBody($client, $url, $actionArray);

        $expectedAction = Utils::deepCopy($action);
        $actionId = self::$actionManager->getLastInserted('action');
        $expectedAction->setId($actionId);
        $expected = Utils::objToArray($expectedAction);

        self::assertEquals($expected, $result);

        // We try to insert the same action a message error must appear because UNIQUE constraint on nom field
        $result = $this->getPostJsonBody($client, $url, $actionArray);

        self::assertEquals(
            ['error' => 'SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: action.nom'],
            $result
        );
    }

    /**
     * Route : /actions/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllActionsWithWrongMethod()
    {
        list($client, $url) = $this->prepareActionRequest('/actions');
        $result = $this->getPostJsonBody($client, $url, []);

        self::assertEquals(['error' => 'Wrong method : POST, use GET method instead'], $result);
    }

    /**
     * Route : /actions/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllActions()
    {
        list($client, $url) = $this->prepareActionRequest('/actions');
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$actionManager->getAll());

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /actions/?([0-9]*)?
     * @depends testExecuteGetAllActions
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUniqueAction()
    {
        $actionId = self::$actionManager->getLastInserted('action');
        list($client, $url) = $this->prepareActionRequest("/actions/$actionId");
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$actionManager->getUnique($actionId));

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /actions/?([0-9]*)?
     * @depends testExecuteGetUniqueAction
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUndefinedAction()
    {
        $actionId = '569997774';
        list($client, $url) = $this->prepareActionRequest("/actions/$actionId");
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['error' => 'No action found!'], $result);
    }

    /**
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function prepareActionRequest(string $url): array
    {
        $client = new Client();
        $url = $this->getFullUrl($url);

        // Prepare Action array
        /** @var  \Entity\Scenario\Action $action */
        $action = Utils::deepCopy(ActionsMock::getActions()[0]);

        /** @var \Entity\Actionneur $actionneur */
        $actionneur = self::$actionneursManager->getUnique($action->getActionneurId());
        $action->setActionneur($actionneur);

        $actionArray = Utils::objToArray($action);
        unset($actionArray['actionneur']);

        return [$client, $url, $action, $actionArray];
    }

    /**
     * @throws \Exception
     */
    public static function removeLastInsertedAction()
    {
        $id = self::$actionManager->getLastInserted('action');
        self::$actionManager->delete($id);
    }

    /**
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        self::removeLastInsertedAction();
    }
}
