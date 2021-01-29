<?php

namespace Tests\e2e\Scenarios;

use GuzzleHttp\Client;
use Model\Scenario\ScenarioManagerPDOFactory;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\Scenarios\mock\ActionsMock;

/**
 * Class ActionsEndpointTest
 * @package Tests\e2e\Mesures
 */
class ActionsEndpointTest extends \Tests\e2e\AbstractEndpointTest
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
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testExecutePost()
    {
        $client = new Client();
        $url = $this->getFullUrl("/actions/add");

        // Prepare Action array
        /** @var  \Entity\Scenario\Action $action */
        $action = Utils::deepCopy(ActionsMock::getActions()[0]);

        /** @var \Entity\Actionneur $actionneur */
        $actionneur = self::$actionneursManager->getUnique($action->getActionneurId());
        $action->setActionneur($actionneur);

        $actionArray = Utils::objToArray($action);
        unset($actionArray['actionneur']);
        $result = $this->getPostJsonBody($client, $url, $actionArray);

        $expectedAction= Utils::deepCopy($action);
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
     * @throws \Exception
     */
    public static function removeLastInsertedAction()
    {
        $id = self::$actionManager->getLastInserted('action');
        $rows = self::$actionManager->delete($id);
    }

    /**
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        self::removeLastInsertedAction();
    }
}
