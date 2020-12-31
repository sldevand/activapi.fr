<?php

namespace Model;

use Entity\ThermostatPlanif;
use Exception;
use OCFram\Entity;

/**
 * Class ThermostatPlanifManagerPDO
 * @package Model
 */
class ThermostatPlanifManagerPDO extends ManagerPDO
{
    /**
     * ThermostatPlanifManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat_planif';
        $this->entity = new ThermostatPlanif();
    }

    /**
     * @return mixed
     */
    public function countPlanifs()
    {
        return $this->dao->query('SELECT COUNT(*) FROM thermostat_corresp')->fetchColumn();
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->dao->exec("DELETE FROM thermostat_corresp WHERE id = $id");
        $this->dao->exec("DELETE FROM $this->tableName WHERE nomid = $id");
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

        /**
         * @var int $key
         * @var ThermostatPlanif[] $thermostatPlanifs
         */
        foreach ($listeThermostatPlanif as $key => $thermostatPlanif) {
            $nom = $this->getNom($thermostatPlanif->nomid());
            $thermostatPlanif->setNom($nom);
            $mode = $this->getMode($thermostatPlanif->modeid());
            $thermostatPlanif->setMode($mode);
            $defaultMode = $this->getMode($thermostatPlanif->defaultModeid());
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

        $thermostatPlanif->setNom($this->getNom($thermostatPlanif->getNomid()));
        $thermostatPlanif->setMode($this->getMode($thermostatPlanif->modeid()));
        $thermostatPlanif->setDefaultMode($this->getMode($thermostatPlanif->defaultModeid()));

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
     * @param Entity $thermostatPlanif
     * @param array $ignoreProperties
     * @return bool|void
     * @throws Exception
     */
    public function add($thermostatPlanif, $ignoreProperties = [])
    {
        $sql = 'INSERT INTO thermostat_planif 
            (jour,modeid,defaultModeid,heure1Start,heure1Stop,heure2Start,heure2Stop,nomid) 
            VALUES          
            (:jour,:modeid,:defaultModeid,:heure1Start,:heure1Stop,:heure2Start,:heure2Stop,:nomid) ';

        $q = $this->prepare($sql);
        $q->bindValue(':jour', $thermostatPlanif->jour());
        $q->bindValue(':modeid', $thermostatPlanif->modeid());
        $q->bindValue(':defaultModeid', $thermostatPlanif->defaultModeid());
        $q->bindValue(':heure1Start', $thermostatPlanif->heure1Start());
        $q->bindValue(':heure1Stop', $thermostatPlanif->heure1Stop());
        $q->bindValue(':heure2Start', $thermostatPlanif->heure2Start());
        $q->bindValue(':heure2Stop', $thermostatPlanif->heure2Stop());
        $q->bindValue(':nomid', $thermostatPlanif->nomid());
        $q->execute();
        $q->closeCursor();
    }


    /**
     * @param \Entity\ThermostatPlanifNom $nom
     * @return int
     * @throws Exception
     */
    public function addPlanifTable($nom)
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
                "heure1Start" => "",
                "heure1Stop" => "",
                "heure2Start" => "",
                "heure2Stop" => "",
                "nomid" => $nomId
            ]);

            $this->add($thermostatPlanif);
        }

        return $nomId;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function getMode($id)
    {
        $sql = 'SELECT * FROM thermostat_modes WHERE id = :id';

        $q = $this->prepare($sql);
        $q->bindValue(':id', $id);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
        $mode = $q->fetch();
        $q->closeCursor();

        return $mode;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getModes()
    {
        $sql = 'SELECT * FROM thermostat_modes';
        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
        $modes = $q->fetchAll();
        $q->closeCursor();

        return $modes;
    }

    /**
     * @param \Entity\ThermostatPlanifNom $name
     * @return string
     * @throws Exception
     */
    public function addNom($name)
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
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function getNom($id)
    {
        $sql = 'SELECT * FROM thermostat_corresp WHERE id = :id';
        $q = $this->prepare($sql);
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
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
}
