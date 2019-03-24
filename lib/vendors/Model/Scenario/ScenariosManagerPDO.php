<?php

namespace Model\Scenario;

use Entity\Scenario\Scenario;
use Entity\Scenario\ScenarioSequence;
use Entity\Scenario\Sequence;
use Model\ManagerPDO;

/**
 * Class ScenariosManagerPDO
 * @package Model\Scenario
 */
class ScenariosManagerPDO extends ManagerPDO
{
    /**
     * @var SequencesManagerPDO $sequencesManagerPDO
     */
    protected $sequencesManagerPDO;

    /**
     * @var ScenarioSequenceManagerPDO
     */
    protected $scenarioSequenceManagerPDO;

    /**
     * ScenariosManagerPDO constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct(\PDO $dao, $args)
    {
        parent::__construct($dao, $args);
        $this->tableName = 'scenario';
        $this->sequencesManagerPDO = $args['sequencesManagerPDO'];
        $this->scenarioSequenceManagerPDO = $args['scenarioSequenceManagerPDO'];
        $this->entity = new Scenario();
    }

    /**
     * @param Scenario $scenario
     * @param array $ignoreProperties
     * @return void
     * @throws \Exception
     */
    public function save($scenario, $ignoreProperties = [])
    {
        parent::save($scenario, ['sequences']);
        $scenarioId = $this->getScenarioId($scenario);
        $sequences = $scenario->getSequences();

        if ($sequences) {
            foreach ($sequences as $sequence) {
                $this->sequencesManagerPDO->save($sequence);
                $sequenceId = $this->sequencesManagerPDO->getSequenceId($sequence);
                $this->scenarioSequenceManagerPDO->save(new ScenarioSequence([
                    'sequenceId' => $sequenceId,
                    'scenarioId' => $scenarioId
                ]));
            }
        }
    }

    public function getUnique($id)
    {
        /** @var Scenario $scenario */
        $scenario = parent::getUnique($id);
        if (empty($scenario)) {
            throw new \Exception('No scenario was found!');
        }

        /** @var Sequence[] $sequences */
        $sequences = $this->getScenarioSequences($id);
        $scenario->setSequences($sequences);

        return $scenario;
    }

    /**
     * @param Scenario $scenario
     * @return int|mixed
     * @throws \Exception
     */
    public function getScenarioId($scenario)
    {
        if (!$scenario->id()) {
            return $this->getLastInserted($this->tableName);
        }

        return $scenario->id();
    }

    /**
     * @param int $scenarioId
     * @return array Sequence[]
     * @throws \Exception
     */
    public function getScenarioSequences($scenarioId)
    {
        $sql = 'SELECT * FROM scenario_sequence as ss
                INNER JOIN scenario s on ss.scenarioId = s.id
                WHERE ss.scenarioId = :scenarioId;';

        $q = $this->prepare($sql);
        $q->bindValue(':scenarioId', $scenarioId);

        $q->execute();

        $sequences = [];
        $rows = $q->fetchAll();

        if ($rows) {
            foreach ($rows as $row) {
                $sequences[] = $this->sequencesManagerPDO->getUnique($row['actionId']);
            }
        }

        return $sequences;
    }
}
