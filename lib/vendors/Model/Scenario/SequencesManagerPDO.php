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
     * SequencesManagerPDO constructor.
     * @param \PDO $dao
     * @param $args
     */
    public function __construct(\PDO $dao, $args)
    {
        parent::__construct($dao, $args);
        $this->tableName = 'sequence';
        $this->actionManagerPDO = $args['actionManagerPDO'];
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
        $actions = $sequence->getActions();
        if ($actions) {
            foreach ($actions as $action) {
                $this->actionManagerPDO->save($action);
            }
        }

        parent::save($sequence, ['actions']);
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

    public function saveSequenceAction(){

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
                WHERE sa.sequenceId = 1;';

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
