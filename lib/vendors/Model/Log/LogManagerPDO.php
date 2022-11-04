<?php

namespace Model\Log;

use Entity\Log\Log;
use Exception;
use Model\ManagerPDO;
use PDO;

/**
 * Class LogManagerPDO
 * @package Model\Log
 */
class LogManagerPDO extends ManagerPDO
{
    /**
     * ActionneursManagerPDO constructor.
     * @param PDO $dao
     */
    public function __construct(PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'log';
        $this->entity = new Log();
    }

    /**
     * @param null|int $from
     * @param null|int $to
     * @return array
     * @throws Exception
     */
    public function getAll($from = null, $to = null)
    {
        $sql = "SELECT * FROM $this->tableName";

        $sql = $this->where('createdAt', $from, $sql, 'from', '>');
        $sql = $this->where('createdAt', $to, $sql, 'to', '<');
        $sql = $this->orderBy('id', $sql, true);
        $sql .= ';';

        $q = $this->prepare($sql);
        $this->bindProperties($q, ['from' => $from, 'to' => $to]);

        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetchAll();
        $q->closeCursor();

        return $entity;
    }

    /**
     * @return int
     */
    public function truncate()
    {
        return $this->dao->exec("DELETE FROM $this->tableName; VACUUM;");
    }
}
