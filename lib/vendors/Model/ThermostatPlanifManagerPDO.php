<?php

namespace Model;

use Entity\ThermostatPlanif;
use Exception;
use OCFram\Managers;

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
        $resPlanif  = $this->dao->exec("DELETE FROM $this->tableName WHERE nomid = $id");

        return $resCorresp && $resPlanif;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getListArray()
    {
        $liste = $this->getAllPlanifs();
        $listeTab = [];
        foreach ($liste as $value) {
            $nomid = (int)$value["id"];
            $listeTab[] = $this->getList($nomid);
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
        $listeThermostatPlanif = $q->fetchAll();
        $q->closeCursor();

        /** @var \Model\ThermostatModesManagerPDO $manager */
        $modesManager = $this->managers->getManagerOf('ThermostatModes');

        /**
         * @var int $key
         * @var ThermostatPlanif[] $thermostatPlanifs
         */
        foreach ($listeThermostatPlanif as $key => $thermostatPlanif) {
            $nom = $this->getNom($thermostatPlanif->nomid());
            $thermostatPlanif->setNom($nom);
            $mode = $modesManager->getUnique($thermostatPlanif->modeid());
            $thermostatPlanif->setMode($mode);
            $defaultMode = $modesManager->getUnique($thermostatPlanif->defaultModeid());
            $thermostatPlanif->setDefaultMode($defaultMode);
        }

        return $listeThermostatPlanif;
    }

    /**
     * @param int $id
     * @return mixed|null|\OCFram\Entity
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

        if (empty($thermostatPlanif->modeid())) {
            throw new \RuntimeException('modeid is null!');
        }

        if (empty($thermostatPlanif->defaultModeid())) {
            throw new \RuntimeException('defaultModeid is null!');
        }
        /** @var \Model\ThermostatModesManagerPDO $manager */
        $modesManager = $this->managers->getManagerOf('ThermostatModes');
        $thermostatPlanif->setNom($this->getNom($thermostatPlanif->getNomid()));
        $thermostatPlanif->setMode($modesManager->getUnique($thermostatPlanif->modeid()));
        $thermostatPlanif->setDefaultMode($modesManager->getUnique($thermostatPlanif->defaultModeid()));

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
            modeid=:modeid,
            defaultModeid=:defaultModeid,
            heure1Start=:heure1Start,
            heure1Stop=:heure1Stop,
            heure2Start=:heure2Start,
            heure2Stop=:heure2Stop           
            WHERE id=:id';

        $q = $this->prepare($sql);
        $q->bindValue(':id', $thermostatPlanif->id());
        $q->bindValue(':modeid', $thermostatPlanif->modeid());
        $q->bindValue(':defaultModeid', $thermostatPlanif->defaultModeid());
        $q->bindValue(':heure1Start', $thermostatPlanif->heure1Start());
        $q->bindValue(':heure1Stop', $thermostatPlanif->heure1Stop());
        $q->bindValue(':heure2Start', $thermostatPlanif->heure2Start());
        $q->bindValue(':heure2Stop', $thermostatPlanif->heure2Stop());
        $result = $q->execute();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param \Entity\ThermostatPlanifNom $nom
     * @return int
     * @throws Exception
     */
    public function addPlanifTable(\Entity\ThermostatPlanifNom $nom)
    {
        $nomId = (int)$this->addNom($nom);
        if ($nomId <= 0) {
            return 0;
        }

        for ($jour = 1; $jour < 8; $jour++) {
            $thermostatPlanif = new ThermostatPlanif([
                "jour" => $jour,
                "modeid" => "1",
                "defaultModeid" => "3",
                "heure1Start" => "07:00",
                "heure1Stop" => "23:00",
                "heure2Start" => "",
                "heure2Stop" => "",
                "nomid" => $nomId
            ]);

            $this->add($thermostatPlanif, $this->getIgnoreProperties());
        }

        return $nomId;
    }

    /**
     * @param \Entity\ThermostatPlanifNom $name
     * @return string
     * @throws Exception
     */
    public function addNom(\Entity\ThermostatPlanifNom $name)
    {
        $thermostaPlanifNoms = $this->getNoms();
        foreach ($thermostaPlanifNoms as $key => $thermostaPlanifNom) {
            if ($thermostaPlanifNom->nom() == $name->nom()) {
                return "Ce Nom existe déjà!";
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
     * @return \Entity\ThermostatPlanifNom
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

    protected function getIgnoreProperties()
    {
        return ['nom', 'defaultMode', 'mode', 'modes'];
    }
}
