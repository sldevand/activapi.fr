<?php

namespace Model\Scenario;

use Entity\Scenario\Scenario;
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
     * ScenariosManagerPDO constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct(\PDO $dao, $args)
    {
        parent::__construct($dao, $args);
        $this->tableName = 'scenario';
        $this->sequencesManagerPDO = $args['sequencesManagerPDO'];
        $this->entity = new Scenario();
    }

    /**
     * @param Scenario $scenario
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($scenario, $ignoreProperties = [])
    {
        $sequences = $scenario->getSequences();
        if ($sequences) {
            foreach ($sequences as $sequence) {
                $this->sequencesManagerPDO->save($sequence);
            }
        }

        return parent::save($scenario, ['sequences']);
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getSequences($id)
    {
        return $this->sequencesManagerPDO->getAll($id);
    }
}
