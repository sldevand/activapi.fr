<?php

namespace Tests\e2e\Scenarios;

use GuzzleHttp\Client;
use Model\Scenario\ScenarioManagerPDOFactory;
use OCFram\PDOFactory;
use SFram\Utils;
use Tests\e2e\AbstractEndpointTest;
use Tests\e2e\Scenarios\mock\SequencesMock;

/**
 * Class SequencesEndpointTest
 * @package Tests\e2e\Scenarios
 */
class SequencesEndpointTest extends AbstractEndpointTest
{
    /** @var \PDO */
    protected static $db;

    /** @var \Model\Scenario\SequencesManagerPDO */
    protected static $sequencesManager;

    public static function setUpBeforeClass()
    {
        $path = $_ENV['DB_PATH_TEST'] ?? $_ENV['DB_PATH'];
        PDOFactory::setPdoAddress($path);
        $scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
        self::$sequencesManager = $scenarioManagerPDOFactory->getSequencesManager();
    }

    /**
     * Route : /sequences/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithWrongMethod()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences/add');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'Wrong method : GET, use POST method instead'], $result);
    }

    /**
     * Route : /sequences/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePostWithEmptyBody()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences/add');

        $result = $this->getPostJsonBody($client, $url, []);
        self::assertEquals(['error' => 'No JSON body sent from client'], $result);
    }

    /**
     * Route : /sequences/add
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePost()
    {
        list($client, $url, $sequence, $sequenceArray) = $this->prepareSequenceRequest('/sequences/add');
        $result = $this->getPostJsonBody($client, $url, $sequenceArray);

        $expectedSequence = Utils::deepCopy($sequence);
        $sequenceId = self::$sequencesManager->getLastInserted('sequence');
        $expectedSequence->setId($sequenceId);
        $expected = Utils::objToArray($expectedSequence);

        self::assertEquals($expected, $result);

        // We try to insert the same sequence a message error must appear because UNIQUE constraint on nom field
        $result = $this->getPostJsonBody($client, $url, $sequenceArray);

        self::assertEquals(
            ['error' => 'SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: sequence.nom'],
            $result
        );
    }

    /**
     * Route : /sequences/update
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePut()
    {
        list($client, $url, $sequence, $sequenceArray) = $this->prepareSequenceRequest('/sequences/update');
        $sequenceId = self::$sequencesManager->getLastInserted('sequence');
        $sequence->setId($sequenceId);
        $sequenceArray['id'] = $sequenceId;
        $sequenceArray['nom'] = 'Test2';

        $result = $this->getPutJsonBody($client, $url, $sequenceArray);

        $expectedSequence = Utils::deepCopy($sequence);
        $expectedSequence->setId($sequenceId);
        $expectedSequence->setNom('Test2');
        $expected = Utils::objToArray($expectedSequence);

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /sequences/update
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithWrongMethod()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences/update');

        $result = $this->getJsonBody($client, $url);
        self::assertEquals(['error' => 'Wrong method : GET, use PUT method instead'], $result);
    }

    /**
     * Route : /sequences/update
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithEmptyBody()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences/update');

        $result = $this->getPutJsonBody($client, $url, []);
        self::assertEquals(['error' => 'No JSON body sent from client'], $result);
    }

    /**
     * Route : /sequences/update
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecutePutWithoutId()
    {
        list($client, $url, $sequence, $sequenceArray) = $this->prepareSequenceRequest('/sequences/update');
        $sequenceId = self::$sequencesManager->getLastInserted('sequence');
        $sequence->setId($sequenceId);
        $sequenceArray['nom'] = 'Test2';

        $result = $this->getPutJsonBody($client, $url, $sequenceArray);

        self::assertEquals(['error' => 'JSON body must contain an id'], $result);
    }

    /**
     * Route : /sequences/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllSequencesWithWrongMethod()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences');
        $result = $this->getPostJsonBody($client, $url, []);

        self::assertEquals(['error' => 'Wrong method : POST, use GET method instead'], $result);
    }

    /**
     * Route : /sequences/?([0-9]*)?
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetAllSequences()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences');
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$sequencesManager->getAll());

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /sequences/?([0-9]*)?
     * @depends testExecuteGetAllSequences
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUniqueSequence()
    {
        $sequenceId = self::$sequencesManager->getLastInserted('sequence');
        list($client, $url) = $this->prepareSequenceRequest("/sequences/$sequenceId");
        $result = $this->getJsonBody($client, $url);
        $expected = Utils::objToArray(self::$sequencesManager->getUnique($sequenceId));

        self::assertEquals($expected, $result);
    }

    /**
     * Route : /sequences/?([0-9]*)?
     * @depends testExecuteGetUniqueSequence
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteGetUndefinedSequence()
    {
        $sequenceId = '569997774';
        list($client, $url) = $this->prepareSequenceRequest("/sequences/$sequenceId");
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['error' => 'No sequence was found!'], $result);
    }

    /**
     * Route : /sequences/delete
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteSequenceWithWrongMethod()
    {
        list($client, $url) = $this->prepareSequenceRequest('/sequences/delete');
        $result = $this->getJsonBody($client, $url);

        self::assertEquals(['error' => 'Wrong method : GET, use DELETE method instead'], $result);
    }

    /**
     * Route : /sequences/delete
     * @depends testExecutePost
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteSequence()
    {
        $sequenceId = self::$sequencesManager->getLastInserted('sequence');
        list($client, $url, , $sequenceArray) = $this->prepareSequenceRequest('/sequences/delete');
        $sequenceArray['id'] = $sequenceId;
        $result = $this->getDeleteJsonBody($client, $url, $sequenceArray);

        self::assertEquals(['success' => 'Test2 has been deleted'], $result);
    }

    /**
     * Route : /sequences/?([0-9]*)?
     * @depends testExecuteGetUniqueSequence
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function testExecuteDeleteUndefinedSequence()
    {
        $sequenceId = '569997774';
        list($client, $url, , $sequenceArray) = $this->prepareSequenceRequest("/sequences/delete");
        $sequenceArray['id'] = $sequenceId;
        $result = $this->getDeleteJsonBody($client, $url, $sequenceArray);

        self::assertEquals(['error' => 'No sequence was found!'], $result);
    }

    /**
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function prepareSequenceRequest(string $url): array
    {
        $client = new Client();
        $url = $this->getFullUrl($url);

        // Prepare Sequence array
        /** @var  \Entity\Scenario\Sequence $sequence */
        $sequence = Utils::deepCopy(SequencesMock::getSequences()[0]);
        $sequenceArray = Utils::objToArray($sequence);

        return [$client, $url, $sequence, $sequenceArray];
    }

    /**
     * @throws \Exception
     */
    public static function removeLastInsertedSequence()
    {
        $id = self::$sequencesManager->getLastInserted('sequence');
        self::$sequencesManager->delete($id);
    }

    /**
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        self::removeLastInsertedSequence();
    }
}
