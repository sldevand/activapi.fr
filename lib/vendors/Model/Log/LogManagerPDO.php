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
     * @param int | null $id
     * @return array
     * @throws Exception
     */
    public function getAll($id = null)
    {
        $sql = "SELECT * FROM $this->tableName";
        if (!empty($id)) {
            $sql .= ' WHERE id=:id';
        }
        $sql.=' ORDER BY createdAt DESC;';

        $q = $this->prepare($sql);
        if (!empty($id)) {
            $q->bindValue(':id', $id);
        }

        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetchAll();
        $q->closeCursor();

        return $entity;
    }
}
