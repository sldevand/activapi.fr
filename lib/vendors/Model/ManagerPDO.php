<?php

namespace Model;

use Exception;
use OCFram\Entity;
use OCFram\Manager;

/**
 * Class ManagerPDO
 * @package Model
 */
class ManagerPDO extends Manager
{
    /**
     * @var string $tableName
     */
    protected $tableName;

    /**
     * @var Entity $entity
     */
    protected $entity;

    /**
     * @param Entity $entity
     * @throws Exception
     */
    public function save(Entity $entity)
    {
        if (!$entity->isValid()) {
            throw new \RuntimeException($entity->erreurs()["notValid"]);
        }

        $entity->isNew() ? $this->add($entity) : $this->update($entity);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM $this->tableName";
        $q = $this->prepare($sql);
        $q->execute();
        $result = $q->fetchColumn();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->dao->exec("DELETE FROM $this->tableName WHERE id = " . (int)$id);
    }

    /**
     * @param Entity $entity
     * @return bool
     * @throws Exception
     */
    public function update(Entity $entity)
    {
        $sql = "UPDATE $this->tableName SET ";
        $properties = $entity->properties();
        $sql .= $this->addProperties($sql, $properties);
        $sql .= "WHERE id = :id";
        $q = $this->prepare($sql);
        $this->bindProperties($q, $properties);
        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param Entity $entity
     * @return bool
     * @throws Exception
     */
    public function add(Entity $entity)
    {
        $sql = "INSERT INTO $this->tableName (";
        $properties = $entity->properties();
        $sql .= $this->addProperties($sql, $properties);
        $sql .= ") VALUES (";
        $sql .= $this->addProperties($sql, $properties);
        $sql .= ")";
        $q = $this->prepare($sql);
        $this->bindProperties($q, $properties);
        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param int $id
     * @return Entity|null
     * @throws \Exception
     */
    public function getUnique($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE id = :id";
        $q = $this->prepare($sql);
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $entityName = "\\Entity\\" . ucfirst(substr($this->tableName, 0, -1));
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $entityName);
        $this->entity = $q->fetch();
        $q->closeCursor();

        return $this->entity;
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return ManagerPDO
     * @throws Exception
     */
    public function setTableName($tableName)
    {
        if (empty($tableName) || !is_string($tableName)) {
            throw new Exception("$tableName is not a string or is empty");
        }
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param string $sql
     * @return \PDOStatement
     * @throws \Exception
     */
    public function prepare($sql)
    {
        $query = $this->dao->prepare($sql);
        if (!$query) {
            throw new \Exception($this->dao->errorInfo());
        }

        return $query;
    }

    /**
     * @param string $sql
     * @return string
     */
    public function addProperties($sql, $properties)
    {
        $count = count($properties) - 2;
        $i = 1;
        foreach ($properties as $key => $property) {
            if ($key !== "id" && $key !== "erreurs") {
                $sql .= $key . " = :" . $key;
                if ($i < $count) {
                    $sql .= ",";
                }
                $sql .= " ";
            }
            $i++;
        }

        return $sql;
    }

    /**
     * @param \PDOStatement $query
     * @param $properties
     * @throws Exception
     */
    public function bindProperties(&$query, $properties)
    {
        if (!$query) {
            throw new \Exception($this->dao->errorInfo());
        }

        foreach ($properties as $key => $property) {
            if ($key !== "erreurs") {
                $query->bindValue(':' . $key, $property);
            }
        }
    }
}
