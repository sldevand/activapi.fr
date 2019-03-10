<?php

namespace Model;

use Entity\Actionneur;
use Entity\Scenario;
use OCFram\Entity;

/**
 * Class ScenariosManagerPDO
 * @package Model
 */
class ScenariosManagerPDO extends ManagerPDO
{
    /** @var ActionneursManagerPDO $actionneursManager */
    protected $actionneursManager;

    /**
     * @param Entity $scenario
     * @return mixed
     * @throws \Exception
     */
    public function add(Entity $scenario)
    {
        if ($this->fetchScenarioCorresp($scenario)) {
            return false;
        }

        $scenarioCorresp = $this->insertScenarioCorresp($scenario);
        $scenario->setScenarioid($scenarioCorresp);
        $this->saveItems($scenario);

        return true;
    }

    /**
     * @param Entity $scenario
     * @throws \Exception
     */
    public function update(Entity $scenario)
    {
        $q = $this->prepare('UPDATE scenario_corresp SET nom = :nom WHERE id = :id');
        $q->bindValue(':id', $scenario->id());
        $q->bindValue(':nom', $scenario->nom());
        $q->execute();
        $q->closeCursor();
        $this->saveItems($scenario);
    }

    /**
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function saveItem($scenario)
    {
        if (!$scenario->id()) {
            $this->insertItem($scenario);
        } else {
            $this->updateItem($scenario);
        }
    }

    /**
     * @param $scenario
     * @return string
     * @throws \Exception
     */
    public function fetchScenarioCorresp($scenario)
    {
        $q = $this->prepare('SELECT * FROM scenario_corresp WHERE nom=:nom');
        $q->bindValue(':nom', $scenario->nom());
        $q->execute();
        $nom = $q->fetchColumn();
        $q->closeCursor();

        return $nom;
    }

    /**
     * @param $scenario
     * @return bool
     * @throws \Exception
     */
    public function insertScenarioCorresp($scenario)
    {
        $q = $this->prepare('INSERT INTO scenario_corresp (nom) VALUES (:nom)');
        $q->bindValue(':nom', $scenario->nom());
        $success = $q->execute();
        $q->closeCursor();

        return $this->fetchScenarioCorresp($scenario);
    }

    /**
     * @param $scenario
     * @return mixed
     * @throws \Exception
     */
    public function insertItem($scenario)
    {
        $q = $this->prepare(
            'INSERT INTO scenario (scenarioid,actionneurid,etat) VALUES (:scenarioid,:actionneurid,:etat)'
        );
        $q->bindValue(':scenarioid', $scenario->scenarioid());
        $q->bindValue(':actionneurid', $scenario->actionneurid());
        $q->bindValue(':etat', $scenario->etat());
        $q->execute();
        $result = $q->fetchColumn();
        $q->closeCursor();

        return $result;
    }


    /**
     * @return mixed
     */
    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM scenario_corresp')->fetchColumn();
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM scenario_corresp WHERE id = ' . (int)$id);
        $this->deleteItemsWithScenarioId($id);
    }

    /**
     * @param int $id
     */
    public function deleteItem($id)
    {
        $this->dao->exec('DELETE FROM scenario WHERE id = ' . (int)$id);
    }

    /**
     * @param int $scenarioId
     */
    public function deleteItemsWithScenarioId($scenarioId)
    {
        $this->dao->exec('DELETE FROM scenario WHERE scenarioid = ' . (int)$scenarioId);
    }

    /**
     * @param ActionneursManagerPDO $actionneursManager
     * @param null $id
     * @return mixed
     * @throws \Exception
     */
    public function getSequences($actionneursManager, $id = null)
    {
        $this->setActionneursManager($actionneursManager);

        return $this->getList($id);
    }

    /**
     * @param null $id
     * @param bool $lazyLoading
     * @return mixed
     * @throws \Exception
     */
    public function getList($id = null, $lazyLoading = false)
    {
        $sql = 'SELECT *
			FROM scenario_corresp 
			INNER JOIN scenario
			ON scenario.scenarioid = scenario_corresp.id
	    ';

        if (!empty($id)) {
            $sql .= " WHERE scenario.scenarioid=:id";
        }

        $q = $this->prepare($sql);
        if (!empty($id)) {
            $q->bindValue(':id', $id);
        }
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Scenario');
        $scenarios = $q->fetchAll();
        $q->closeCursor();

        if ($lazyLoading) {
            return $scenarios;
        }

        return $this->setActionneursOnScenarios($scenarios);
    }

    /**
     * @param Scenario[] $scenarios
     * @return array
     * @throws \Exception
     */
    protected function setActionneursOnScenarios($scenarios)
    {
        $scenariosTab = [];
        /** @var Actionneur[] $actionneurs */
        $actionneurs = $this->actionneursManager->getList();

        foreach ($scenarios as $scenario) {
            $scenariosTab[$scenario->scenarioid()]["nom"] = $scenario->nom();
            $scenariosTab[$scenario->scenarioid()]["scenarioid"] = $scenario->scenarioid();
            $actionneutTemp = $actionneurs[$scenario->actionneurid()];
            $actionneutTemp->setEtat($scenario->etat());
            $scenariosTab[$scenario->scenarioid()]["data"][$scenario->id()] = $actionneutTemp;
        }

        return $scenariosTab;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function getScenarioByName($name)
    {
        $sql = 'SELECT scenario.scenarioid,scenario_corresp.nom,scenario.actionneurid,scenario.etat
			FROM scenario_corresp 
			INNER JOIN scenario
			ON scenario.scenarioid = scenario_corresp.id	   
		    WHERE scenario_corresp.nom=:nom
	    ';

        $q = $this->prepare($sql);
        $q->bindValue(':nom', $name);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Scenario');

        $result = $q->fetch();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getName($id)
    {
        $sql = 'SELECT scenario_corresp.nom as nom   
			   FROM scenario_corresp
			   INNER JOIN scenario
	          ON scenario.scenarioid = scenario_corresp.id
	          WHERE scenario.id=:id
	';

        $q = $this->prepare($sql);
        $q->bindParam(':id', $id);
        $q->execute();
        $result = $q->fetchColumn();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param Entity $scenario
     * @throws \Exception
     */
    public function updateItem(Entity $scenario)
    {
        $q = $this->prepare(
            'UPDATE scenario SET  actionneurid = :actionneurid, etat = :etat WHERE id = :id'
        );

        $q->bindValue(':id', $scenario->id());
        $q->bindValue(':actionneurid', $scenario->actionneurid());
        $q->bindValue(':etat', $scenario->etat());
        $result = $q->execute();
        $q->closeCursor();
    }

    /**
     * @param int $scenarioId
     * @return array
     * @throws \Exception
     */
    public function getSequence($scenarioId = null)
    {
        if (!$scenarioId) {
            return [new Scenario()];
        }

        $q = $this->prepare(
            'SELECT * FROM scenario WHERE scenarioid = :scenarioid'
        );
        $q->bindValue(':scenarioid', (int)$scenarioId);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Scenario::class);
        $result = $q->fetchAll();
        $q->closeCursor();

        return $result;
    }

    /**
     * @param Entity $scenario
     * @throws \Exception
     */
    public function saveItems($scenario)
    {
        foreach ($scenario->actionneurs() as $actionneur) {
            $scenario->setId($actionneur->getRadioId());
            $scenario->setActionneurId($actionneur->id());
            $scenario->setEtat($actionneur->getEtat());
            $this->saveItem($scenario);
        }
    }

    /**
     * @return ActionneursManagerPDO
     */
    public function getActionneursManager()
    {
        return $this->actionneursManager;
    }

    /**
     * @param ActionneursManagerPDO $actionneursManager
     * @return ScenariosManagerPDO
     */
    public function setActionneursManager($actionneursManager)
    {
        $this->actionneursManager = $actionneursManager;

        return $this;
    }
}
