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
     * @param array $ignoreProperties
     * @return bool
     * @throws Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        if (!$entity->isValid($ignoreProperties)) {
            throw new \RuntimeException($entity->erreurs()["notValid"]);
        }

        return $entity->isNew() ? $this->add($entity, $ignoreProperties) : $this->update($entity, $ignoreProperties);
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
     * @param $ignoreProperties
     * @return bool
     * @throws Exception
     */
    public function update($entity, $ignoreProperties = null)
    {
        $sql = "UPDATE $this->tableName SET ";
        $properties = $this->ignoreProperties($entity, $ignoreProperties);
        $sql = $this->addProperties($sql, $properties);
        $sql .= "WHERE id = :id";
        $q = $this->prepare($sql);
        $this->bindProperties($q, $properties);
        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws Exception
     */
    public function add($entity, $ignoreProperties = [])
    {
        $properties = $this->ignoreProperties($entity, $ignoreProperties);
        $sql = "INSERT INTO $this->tableName (";
        $sql .= $this->addInsertProperties($properties);
        $sql .= ") VALUES (";
        $sql .= $this->addInsertProperties($properties, true);
        $sql .= ");";
        $q = $this->prepare($sql);
        foreach ($properties as $key => $property) {
            if ($key !== "erreurs" && $key !== 'id') {
                $q->bindValue(":$key", $property);
            }
        }

        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param int $id
     * @return Entity|null
     * @throws Exception
     */
    public function getUnique($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE id = :id";
        $q = $this->prepare($sql);
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetch();
        $q->closeCursor();

        return $entity;
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

        $q = $this->prepare($sql);

        if (!is_null($id) && !empty($id)) {
            $q->bindValue(':id', $id);
        }

        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetchAll();
        $q->closeCursor();

        return $entity;
    }


    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return array
     */
    protected function ignoreProperties(Entity $entity, $ignoreProperties = null)
    {
        if (empty($ignoreProperties)) {
            return $entity->properties();
        }

        $properties = [];
        foreach ($entity->properties() as $key => $property) {
            if (!in_array($key, $ignoreProperties)) {
                $properties[$key] = $property;
            }
        }

        return $properties;
    }


    /**
     * @return null|string
     */
    public function getEntityName()
    {
        return get_class($this->entity);
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
            throw new \Exception(implode(" ", $this->dao->errorInfo()));
        }

        return $query;
    }

    /**
     * @param string $sql
     * @param array $properties
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
     * @param array $properties
     * @param bool $isValue
     * @return string
     */
    public function addInsertProperties($properties, $isValue = false)
    {
        $count = count($properties) - 2;
        $i = 1;
        $sql = '';
        foreach ($properties as $key => $property) {
            if ($key !== "id" && $key !== "erreurs") {
                if ($isValue) {
                    $sql .= ':';
                }
                $sql .= $key;
                if ($i < $count) {
                    $sql .= ",";
                }
//                $sql .= " ";
            }
            $i++;
        }

        return $sql;
    }


    /**
     * @param \PDOStatement $query
     * @param $properties
     * @return \PDOStatement
     * @throws Exception
     */
    public function bindProperties($query, $properties)
    {
        if (!$query) {
            throw new Exception($this->dao->errorInfo());
        }

        foreach ($properties as $key => $property) {
            if ($key !== "erreurs") {
                $query->bindValue(':' . $key, $property);
            }
        }

        return $query;
    }
}
