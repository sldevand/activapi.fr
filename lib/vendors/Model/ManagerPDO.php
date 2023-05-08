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
     * @return int
     */
    public function delete($id)
    {
        $this->dao->exec("PRAGMA foreign_keys=on");
        return $this->dao->exec("DELETE FROM $this->tableName WHERE id = " . (int)$id);
    }

    /**
     * @param Entity $entity
     * @param $ignoreProperties
     * @return bool
     * @throws Exception
     */
    public function update($entity, $ignoreProperties = null)
    {
        if (!$entity->isValid($ignoreProperties)) {
            throw new \RuntimeException($entity->erreurs()["notValid"]);
        }

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
        if (!$entity->isValid($ignoreProperties)) {
            throw new \RuntimeException($entity->erreurs()["notValid"]);
        }

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
     * @param string $field
     * @param string $value
     * @return Entity|null
     * @throws \Exception
     */
    public function getUniqueBy(string $field, string $value)
    {
        $sql = "SELECT * FROM $this->tableName WHERE $field = :$field";
        $q = $this->prepare($sql);
        $q->bindValue(":$field", $value);
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
        if (!empty($id)) {
            $q->bindValue(':id', $id);
        }

        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entity = $q->fetchAll();
        $q->closeCursor();

        return $entity;
    }

    /**
     * @param null $id
     * @return array
     * @throws Exception
     */
    public function getList($id = null)
    {
        return $this->getAll($id);
    }

    /**
     * @param string $table
     * @return mixed
     * @throws \Exception
     */
    public function getLastInserted($table)
    {
        $sql = 'SELECT seq FROM sqlite_sequence WHERE name="' . $table . '"';
        $q = $this->query($sql);
        $q->execute();
        $res = $q->fetchColumn();

        return $res;
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
     * @throws Exception
     */
    public function prepare($sql)
    {
        $query = $this->dao->prepare($sql);
        if (!$query) {
            throw new Exception(implode(" ", $this->dao->errorInfo()));
        }

        return $query;
    }

    /**
     * @param string $sql
     * @return \PDOStatement
     * @throws Exception
     */
    public function query($sql)
    {
        $query = $this->dao->query($sql);
        if (!$query) {
            throw new Exception(implode(" ", $this->dao->errorInfo()));
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
        unset($properties['id']);
        unset($properties['erreurs']);
        $count = count($properties);
        $i = 1;
        $sql = '';
        foreach ($properties as $key => $property) {
            if ($isValue) {
                $sql .= ':';
            }
            $sql .= $key;
            if ($i < $count) {
                $sql .= ",";
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
            $errorInfo = implode(' | ' , $this->dao->errorInfo());
            throw new Exception($errorInfo);
        }

        foreach ($properties as $key => $value) {
            if ($key !== "erreurs" && $value !== null) {
                $query->bindValue(':' . $key, $value);
            }
        }

        return $query;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $sql
     * @param string|null $bind
     * @param string $operator
     * @return string
     */
    protected function where($field, $value, $sql, $bind = null, $operator = '=')
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return $sql;
        }

        $where = 'WHERE';
        if (strstr($sql, 'WHERE') !== false) {
            $where = 'AND';
        }

        if (empty($bind)) {
            $bind = $field;
        }

        return <<<SQL
$sql
$where $field $operator :$bind
SQL;
    }

    /**
     * @param string $field
     * @param string $sql
     * @param bool $desc
     * @return string
     */
    protected function orderBy($field, $sql, $desc = false)
    {
        $order = ($desc) ? 'DESC' : '';

        return <<<SQL
$sql
ORDER BY $field $order
SQL;
    }
}
