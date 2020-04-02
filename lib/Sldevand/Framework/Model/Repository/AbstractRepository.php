<?php

namespace Framework\Model\Repository;

use Framework\Api\EntityInterface;
use Framework\Api\RepositoryInterface;
use Framework\Exception\RepositoryException;
use PDO;

/**
 * Class AbstractRepository
 * @package Framework\Model\Repository
 */
class AbstractRepository implements RepositoryInterface
{
    /** @var Pdo */
    protected $connection;

    /** @var string */
    protected $table;

    /** @var EntityInterface */
    protected $entity;

    /**
     * AbstractRepository constructor.
     * @param Pdo $connection
     */
    public function __construct(Pdo $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity): EntityInterface
    {
        if (empty($entity->getId())) {
            return $this->create($entity);
        }

        return $this->update($entity);
    }

    /**
     * @param int | string $value
     * @param string $field
     * @return EntityInterface
     * @throws RepositoryException
     */
    public function get($value, string $field = 'id'): EntityInterface
    {
        $sql = "SELECT * FROM $this->table WHERE $field=:$value";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue($field, $value);
        $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entity);
        if (!$statement->execute()) {
            throw new RepositoryException('This Entity was not found');
        }

        return $statement->fetch();
    }

    /**
     * @param array $filter
     * @param int $limit
     * @return array
     * @throws RepositoryException
     */
    public function getList(array $filter = [], int $limit = 0): array
    {
        $sql = "SELECT * FROM $this->table";

        $bindValues = [];
        if (!empty($filter)) {
            $bindValues = $this->prepareSqlWithFilter($sql, $filter);
        }

        if (!empty($limit)) {
            $sql .= " LIMIT = $limit";
        }

        $statement = $this->connection->prepare($sql);

        foreach ($bindValues as $index => $value) {
            $statement->bindValue($index, $value);
        }

        $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entity);
        if (!$statement->execute()) {
            throw new RepositoryException('This Entity was not found');
        }

        return $statement->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $filter
     * @return array
     */
    protected function prepareSqlWithFilter(string &$sql, array $filter)
    {
        $count = 0;
        $bindValues = [];
        foreach ($filter as $field => $condition) {
            $where = ($count === 0) ? 'WHERE' : 'AND';
            $sql .= " $where $field";
            foreach ($condition as $alias => $values) {
                $operator = $this->aliasToOperator($alias);

                if (!is_array($values)) {
                    $values = [$values];
                }

                $rightOperand = '(';
                foreach ($values as $index => $value) {
                    $bindValues[$index + 1] = $value;
                    $rightOperand .= '?';
                    if ($index < count($values) - 1) {
                        $sql .= ',';
                    }
                }
                $rightOperand .= ')';

                $sql .= "$operator $rightOperand";
            }
            $count++;
        }

        return $bindValues;
    }

    /**
     * @param string $alias
     * @return string
     */
    protected function aliasToOperator($alias)
    {
        $aliases = [
            'eq' => '=',
            'neq' => '<>',
            'gt' => '>',
            'gte' => '>=',
            'lt' => '>=',
            'lte' => '<='
        ];

        return $aliases[$alias];
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface
    {
        // TODO: Implement create() method.
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function update(EntityInterface $entity): EntityInterface
    {
        // TODO: Implement update() method.
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
