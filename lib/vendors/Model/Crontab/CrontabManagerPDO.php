<?php

namespace Model\Crontab;

use Entity\Crontab\Crontab;
use Model\ManagerPDO;

/**
 * Class CrontabManagerPDO
 * @package Model\Crontab
 */
class CrontabManagerPDO extends ManagerPDO
{
    /**
     * CrontabManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'crontab';
        $this->entity = new Crontab();
    }

    /**
     * @param string $field
     * @param string $value
     * @return array
     * @throws \Exception
     */
    public function getListLike(string $field, string $value)
    {
        $sql = <<<SQL
SELECT * FROM $this->tableName WHERE $field LIKE "%$value%" AND active=1
SQL;

        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetchAll();
        $q->closeCursor();

        return $entity;
    }
}
