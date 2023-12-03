<?php

namespace Model;

use Entity\Mesure;
use Entity\Sensor;
use OCFram\Managers;
use OCFram\PDOFactory;

/**
 * Class MesuresManagerPDO
 * @package Model
 */
class MesuresManagerPDO extends ManagerPDO
{
    /** @var \OCFram\Managers */
    protected $managers;

    /**
     * MesuresManagerPDO constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct(\PDO $dao, $args = [])
    {
        parent::__construct($dao, $args);
        $this->tableName = 'mesures';
        $this->entity = new Mesure();
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
    }

    /**
     * @param \OCFram\Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        $ignoreProperties = array_merge($ignoreProperties, ['nom']);

        return parent::save($entity, $ignoreProperties);
    }

    /**
     * @param \Entity\Sensor $sensor
     * @return bool
     * @throws \Exception
     */
    public function addWithSensorId(Sensor $sensor)
    {
        if (!$sensor = $this->getSensor($sensor->radioid())) {
            throw new \Exception('In addWithSensorId, sensor not found with radioid :' . $sensor->radioid());
        }
        if (is_array($sensor)) {
            $sensor = current($sensor);
        }

        $mesure = new Mesure(
            [
                'id_sensor' => (string)$sensor->id(),
                'temperature' => $sensor->valeur1(),
                'hygrometrie' => $sensor->valeur2(),
                'horodatage' => date("Y-m-d H:i:s")
            ]
        );

        return $this->add($mesure, ['nom']);
    }

    /**
     * @param int $debut
     * @param int $limite
     * @return array
     * @throws \Exception
     */
    public function getList($debut = 0, $limite = 50)
    {
        $sql = 'SELECT s.radioid id_sensor,s.nom, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        if (!$q = $this->dao->query($sql)) {
            throw new \Exception($this->dao->errorInfo());
        }

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');
        $listeMesure = $q->fetchAll();
        $q->closeCursor();

        return $listeMesure;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getListCount()
    {
        $sql = 'SELECT COUNT(*) FROM mesures';

        if (!$q = $this->dao->query($sql)) {
            throw new \Exception($this->dao->errorInfo());
        }

        $result = $q->fetchColumn();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param string $sensor
     * @param string $dateMin
     * @param string $dateMax
     * @return array
     * @throws \Exception
     */
    public function getSensorList($sensor, $dateMin, $dateMax)
    {
        $sql = 'SELECT s.radioid id_sensor,s.nom nom, s.id, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id
			AND s.radioid= :id_sensor
			AND horodatage >= :dateMin
			AND horodatage <= :dateMax
			ORDER BY horodatage ASC';

        $q = $this->prepare($sql);
        $q->bindParam(':id_sensor', $sensor);
        $q->bindParam(':dateMin', $dateMin);
        $q->bindParam(':dateMax', $dateMax);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');
        $listeMesure = $q->fetchAll();
        $q->closeCursor();

        return $listeMesure;
    }

    /**
     * @param mixed $value
     * @param string $field
     * @return \OCFram\Entity
     * @throws \Exception
     */
    public function getSensor($value, $field = 'radioid')
    {
        return $this->managers->getManagerOf('Sensors')->getUniqueBy($field, $value);
    }

    /**
     * @param array $categories
     * @return Sensor[]
     * @throws \Exception
     */
    public function getSensors(array $categories = []): array
    {
        $sql = "SELECT * FROM sensors";
        if ($categories) {
            $categoriesSql = implode('","', $categories);
            $sql .= ' WHERE categorie IN ("' . $categoriesSql . '")';
        }
        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $sensors = $q->fetchAll();
        $q->closeCursor();

        return $sensors;
    }

    /**
     * @param array $categories
     * @return string[]
     * @throws \Exception
     */
    public function getSensorsRadioIds(array $categories = []): array
    {
        $sensors = $this->getSensors($categories);

        return array_map(function ($sensor) {
            return $sensor->radioid();
        }, $sensors);
    }

    /**
     * @return false|int
     */
    public function removeOrphanRows()
    {
        $sql = <<<SQL
DELETE FROM mesures WHERE ROWID IN (
    SELECT m.ROWID
    FROM mesures m
    LEFT JOIN sensors s ON m.id_sensor = s.id
    WHERE s.id IS NULL
);
SQL;

        return $this->dao->exec($sql);
    }
}
