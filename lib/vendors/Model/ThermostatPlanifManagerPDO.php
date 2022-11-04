<?php

namespace Model;

use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;
use Exception;
use OCFram\Managers;
use SFram\Utils;

/**
 * Class ThermostatPlanifManagerPDO
 * @package Model
 */
class ThermostatPlanifManagerPDO extends ManagerPDO
{
    /** @var \OCFram\Managers */
    protected $managers;

    /**
     * ThermostatPlanifManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat_planif';
        $this->entity = new ThermostatPlanif();
        $this->managers = new Managers('PDO', $dao);
    }

    /**
     * @param int $id
     * @return bool|int
     */
    public function delete($id)
    {
        $resCorresp = $this->dao->exec("DELETE FROM thermostat_corresp WHERE id = $id");
        $resPlanif = $this->dao->exec("DELETE FROM $this->tableName WHERE nomid = $id");

        return $resCorresp && $resPlanif;
    }

    /**
     * @return bool|int
     */
    public function deleteAll()
    {
        $resCorresp = $this->dao->exec("DELETE FROM thermostat_corresp");
        $resPlanif = $this->dao->exec("DELETE FROM $this->tableName");

        return $resCorresp && $resPlanif;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getListArray()
    {
        $allPlanifs = $this->getAllPlanifs();
        $listeTab = [];
        foreach ($allPlanifs as $planif) {
            $nomid = (int)$planif["id"];
            $listeTab[$nomid] = $this->getList($nomid);
        }

        return $listeTab;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getList($id = null)
    {
        $sql = 'SELECT * FROM thermostat_planif';

        if (!empty($id)) {
            $sql .= ' WHERE nomid=:nomid';
        }

        $q = $this->prepare($sql);

        if (!is_null($id) && !empty($id)) {
            $q->bindValue(':nomid', $id);
        }
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanif');
        $thermostatPlanifs = $q->fetchAll();
        $q->closeCursor();

        /** @var ThermostatPlanif[] $thermostatPlanifs */
        foreach ($thermostatPlanifs as $thermostatPlanif) {
            $nom = $this->getNom($thermostatPlanif->getNomId());
            $thermostatPlanif->setNom($nom);
        }

        return $thermostatPlanifs;
    }

    /**
     * @param int $id
     * @return false|ThermostatPlanif
     * @throws Exception
     */
    public function getUnique($id)
    {
        $sql = 'SELECT * FROM thermostat_planif WHERE id = :id';
        $q = $this->prepare($sql);
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanif');
        $thermostatPlanif = $q->fetch();
        $q->closeCursor();

        /** @var ThermostatPlanif $thermostatPlanif */
        if (!$thermostatPlanif) {
            return false;
        }

        $thermostatPlanif->setNom($this->getNom($thermostatPlanif->getNomid()));

        return $thermostatPlanif;
    }

    /**
     * @param ThermostatPlanif $thermostatPlanif
     * @param array $ignoreProperties
     * @return bool|int
     * @throws Exception
     */
    public function save($thermostatPlanif, $ignoreProperties = [])
    {
        if (!$thermostatPlanif->isValid($ignoreProperties)) {
            throw new \RuntimeException('Le thermostatPlanif doit être valide pour être enregistré');
        }

        return $thermostatPlanif->isNew()
            ? $this->addPlanifTable($thermostatPlanif->getNom())
            : $this->modify($thermostatPlanif);
    }

    /**
     * @param ThermostatPlanif $thermostatPlanif
     * @return bool
     * @throws Exception
     */
    public function modify(ThermostatPlanif $thermostatPlanif)
    {
        $sql = 'UPDATE thermostat_planif 
            SET             
            timetable=:timetable
            WHERE id=:id';

        $q = $this->prepare($sql);
        $q->bindValue(':id', $thermostatPlanif->id());
        $q->bindValue(':timetable', $thermostatPlanif->getTimetable());
        $result = $q->execute();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param ThermostatPlanifNom $nom
     * @return int
     * @throws Exception
     */
    public function addPlanifTable(ThermostatPlanifNom $nom)
    {
        $nomId = (int)$this->addNom($nom);
        if ($nomId <= 0) {
            return 0;
        }

        for ($jour = 1; $jour < 8; $jour++) {
            $thermostatPlanif = new ThermostatPlanif([
                "jour" => $jour,
                "timetable" => json_encode(['300-1', '600-2', '800-1', '1200-3']),
                "nomid" => $nomId
            ]);

            $this->add($thermostatPlanif, $this->getIgnoreProperties());
        }

        return $nomId;
    }

    /**
     * @param ThermostatPlanifNom $name
     * @return string
     * @throws Exception
     */
    public function addNom(ThermostatPlanifNom $name)
    {
        $thermostaPlanifNoms = $this->getNoms();
        foreach ($thermostaPlanifNoms as $key => $thermostaPlanifNom) {
            if ($thermostaPlanifNom->nom() == $name->nom()) {
                throw new \Exception('Ce nom existe déjà !');
            }
        }

        $sql = 'INSERT INTO thermostat_corresp (nom) VALUES (:nom)';
        $q = $this->prepare($sql);
        $q->bindValue(':nom', $name->nom());
        $q->execute();

        return $this->dao->lastInsertId();
    }

    /**
     * @param int | string $value
     * @param string $field
     * @return ThermostatPlanifNom
     * @throws \Exception
     */
    public function getNom($value, $field = 'id')
    {
        $sql = "SELECT * FROM thermostat_corresp WHERE $field = :value";
        $q = $this->prepare($sql);
        $q->bindValue(':value', $value);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanifNom');
        $nom = $q->fetch();
        $q->closeCursor();

        return $nom;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getNoms()
    {
        $sql = 'SELECT * FROM thermostat_corresp';
        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanifNom');
        $noms = $q->fetchAll();
        $q->closeCursor();

        return $noms;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAllPlanifs()
    {
        $sql = 'SELECT * FROM thermostat_corresp';
        $q = $this->prepare($sql);
        $q->execute();
        $planifs = $q->fetchAll();
        $q->closeCursor();

        return $planifs;
    }

    /**
     * @param string $id
     * @param string $nom
     * @throws Exception
     */
    public function duplicate(string $id, string $nom)
    {
        $nomToAdd = new ThermostatPlanifNom(['nom' => $nom]);
        $nomId = $this->addNom($nomToAdd);
        $planifDays = $this->getList($id);
        foreach ($planifDays as $planifDay) {
            $planifDayArray = Utils::objToArray($planifDay);
            unset($planifDayArray['id']);
            unset($planifDayArray['nom']);
            $planifDayArray['nomid'] = $nomId;
            $this->add(new ThermostatPlanif($planifDayArray), $this->getIgnoreProperties());
        }
    }

    /**
     * @return array
     */
    protected function getIgnoreProperties(): array
    {
        return ['nom', 'defaultMode', 'mode', 'modes'];
    }

    /**
     * @param int $nomid
     * @param int $day
     * @return \Entity\ThermostatPlanif | false
     */
    public function getByNomIdAndDay($nomid, $day)
    {
        $sql = 'SELECT * FROM thermostat_planif WHERE nomid = :nomid AND jour = :jour';
        $q = $this->prepare($sql);
        $q->bindValue(':nomid', (int)$nomid, \PDO::PARAM_INT);
        $q->bindValue(':jour', (int)$day, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, \Entity\ThermostatPlanif::class);
        $thermostatPlanif = $q->fetch();
        $q->closeCursor();

        /** @var ThermostatPlanif $thermostatPlanif */
        if (!$thermostatPlanif) {
            return false;
        }

        $thermostatPlanif->setNom($this->getNom($nomid));

        return $thermostatPlanif;
    }
}
