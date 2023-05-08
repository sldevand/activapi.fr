<?php

namespace Model\Scenario;

use Entity\Scenario\Scenario;
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
     * @return int
     * @throws \Exception
     */
    public function save($scenario, $ignoreProperties = [])
    {
        parent::save($scenario, ['sequences', 'scenarioSequences']);
        $scenarioId = $this->getScenarioId($scenario);
        if (!$scenarioSequences = $scenario->getScenarioSequences()) {
            return $scenarioId;
        }

        foreach ($scenarioSequences as $scenarioSequence) {
            $scenarioSequence->setScenarioId($scenarioId);
            $this->scenarioSequenceManagerPDO->save($scenarioSequence);
        }

        return $scenarioId;
    }

    /**
     * @param int $id
     * @return Scenario
     * @throws \Exception
     */
    public function getUnique($id): Scenario
    {
        /** @var Scenario $scenario */
        $scenario = parent::getUnique($id);
        if (empty($scenario)) {
            throw new \Exception('No scenario was found!');
        }

        $this->linkSequences([$scenario]);

        return $scenario;
    }

    /**
     * @param int | null $id
     * @return Scenario[]
     * @throws \Exception
     */
    public function getAll($id = null, $visibleOnly = true): array
    {
        /** @var Scenario[] $scenarios */
        $scenarios = $visibleOnly ? $this->getVisibleScenarios() : parent::getAll($id);
        if (empty($scenarios)) {
            throw new \Exception('No scenarios were found!');
        }
        $this->linkSequences($scenarios);

        return $scenarios;
    }

    /**
     * @param array $scenarios
     * @return void
     * @throws \Exception
     */
    protected function linkSequences(array $scenarios): void
    {
        foreach ($scenarios as $scenario) {
            /** @var Sequence[] $sequences */
            $sequences = $this->getScenarioSequences($scenario->id());
            $scenario->setSequences($sequences);
        }
    }

    /**
     * @return Scenario[]
     * @throws \Exception
     */
    protected function getVisibleScenarios(): array
    {
        $sql = "SELECT * FROM $this->tableName WHERE visibility=1";
        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $scenarios = $q->fetchAll();
        $q->closeCursor();

        if (empty($scenarios)) {
            throw new \Exception('No scenarios were found!');
        }

        return $scenarios;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        parent::delete($id);

        return $this->deleteScenarioSequences($id);
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

        if (!$rows = $q->fetchAll()) {
            return [];
        }

        $sequences = [];
        foreach ($rows as $row) {
            $sequences[$row[0]] = $this->sequencesManagerPDO->getUnique($row['sequenceId']);
        }

        return $sequences;
    }

    /**
     * @param int $scenarioId
     * @return bool
     * @throws \Exception
     */
    public function deleteScenarioSequences($scenarioId)
    {
        $sql = 'DELETE FROM scenario_sequence
                WHERE scenarioId = :scenarioId;';

        $q = $this->prepare($sql);
        $q->bindValue(':scenarioId', $scenarioId);

        return $q->execute();
    }

    public function resetScenarioStatuses()
    {
        $sql = "UPDATE scenario SET status = 'stop';";

        $q = $this->prepare($sql);

        return $q->execute();
    }
}
