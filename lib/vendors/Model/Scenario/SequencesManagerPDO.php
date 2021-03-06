<?php

namespace Model\Scenario;

use Entity\Scenario\Action;
use Entity\Scenario\Sequence;
use Model\ManagerPDO;

/**
 * Class SequencesManagerPDO
 * @package Model\Scenario
 */
class SequencesManagerPDO extends ManagerPDO
{
    /**
     * @var ActionManagerPDO $actionManagerPDO
     */
    protected $actionManagerPDO;

    /**
     * @var SequenceActionManagerPDO $sequenceActionManagerPDO
     */
    protected $sequenceActionManagerPDO;

    /**
     * SequencesManagerPDO constructor.
     * @param \PDO $dao
     * @param $args
     */
    public function __construct(\PDO $dao, $args)
    {
        parent::__construct($dao, $args);
        $this->tableName = 'sequence';
        $this->actionManagerPDO = $args['actionManagerPDO'];
        $this->sequenceActionManagerPDO = $args['sequenceActionManagerPDO'];
        $this->entity = new Sequence();
    }

    /**
     * @param Sequence $sequence
     * @param array $ignoreProperties
     * @return int
     * @throws \Exception
     */
    public function save($sequence, $ignoreProperties = [])
    {

        parent::save($sequence, ['actions', 'sequenceActions']);
        $sequenceId = $this->getSequenceId($sequence);

        if (!$sequenceActions = $sequence->getSequenceActions()) {
            return $sequenceId;
        }

        foreach ($sequenceActions as $sequenceAction) {
            $this->sequenceActionManagerPDO->save($sequenceAction);
        }

        return $sequenceId;
    }

    /**
     * @param Sequence $sequence
     * @return int|mixed
     * @throws \Exception
     */
    public function getSequenceId($sequence)
    {
        if (!$sequence->id()) {
            return $this->getLastInserted($this->tableName);
        }

        return $sequence->id();
    }

    /**
     * @param int $id
     * @return Sequence
     * @throws \Exception
     */
    public function getUnique($id)
    {
        /** @var Sequence $sequence */
        $sequence = parent::getUnique($id);
        if (empty($sequence)) {
            throw new \Exception('No sequence was found!');
        }

        /** @var Action[] $actions */
        $actions = $this->getSequenceActions($id);
        $sequence->setActions($actions);

        return $sequence;
    }

    public function getAll($id = null)
    {
        /** @var Sequence[] $sequences */
        $sequences = parent::getAll($id);
        if (empty($sequences)) {
            throw new \Exception('No sequences were found!');
        }

        foreach ($sequences as $key => $sequence) {
            /** @var Action[] $actions */
            $actions = $this->getSequenceActions($sequence->id());
            $sequences[$key]->setActions($actions);
        }

        return $sequences;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        parent::delete($id);

        return $this->deleteSequenceActions($id);
    }

    /**
     * @param int $sequenceId
     * @return array $Action[]
     * @throws \Exception
     */
    public function getSequenceActions($sequenceId)
    {
        $sql = 'SELECT * FROM sequence_action as sa
                INNER JOIN sequence s on sa.sequenceId = s.id
                WHERE sa.sequenceId = :sequenceId;';

        $q = $this->prepare($sql);
        $q->bindValue(':sequenceId', $sequenceId);
        $q->execute();

        if (!$rows = $q->fetchAll()) {
            return [];
        }

        $actions = [];
        foreach ($rows as $row) {
            $actions[$row[0]] = $this->actionManagerPDO->getUnique($row['actionId']);
        }

        return $actions;
    }

    /**
     * @param int $sequenceId
     * @return bool
     * @throws \Exception
     */
    public function deleteSequenceActions($sequenceId)
    {
        $sql = 'DELETE FROM sequence_action
                WHERE sequenceId = :sequenceId;';

        $q = $this->prepare($sql);
        $q->bindValue(':sequenceId', $sequenceId);

        return $q->execute();
    }
}
