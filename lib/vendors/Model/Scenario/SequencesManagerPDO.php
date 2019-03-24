<?php

namespace Model\Scenario;

use Entity\Scenario\Action;
use Entity\Scenario\Sequence;
use Entity\Scenario\SequenceAction;
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
     * @return void
     * @throws \Exception
     */
    public function save($sequence, $ignoreProperties = [])
    {
        parent::save($sequence, ['actions']);
        $actions = $sequence->getActions();
        $sequenceId = $this->getSequenceId($sequence);

        if ($actions) {
            foreach ($actions as $action) {
                $this->actionManagerPDO->save($action);
                $actionId = $this->actionManagerPDO->getActionId($action);
                $this->sequenceActionManagerPDO->save(new SequenceAction([
                    'sequenceId' => $sequenceId,
                    'actionId' => $actionId
                ]));
            }
        }
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
        if ($sequence) {
            /** @var Action[] $actions */
            $actions = $this->getSequenceActions($id);
            $sequence->setActions($actions);
        }

        return $sequence;
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

        $actions = [];
        $rows = $q->fetchAll();

        if ($rows) {
            foreach ($rows as $row) {
                $actions[] = $this->actionManagerPDO->getUnique($row['actionId']);
            }
        }

        return $actions;
    }
}
