<?php

namespace Model\Scenario;

use Entity\Scenario\Sequence;
use Model\ManagerPDO;
use OCFram\Entity;

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
     * @param Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        return parent::save($entity, ['actions']);
    }

    //TODO implement getAll here
}
