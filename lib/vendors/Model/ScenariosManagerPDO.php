<?php

namespace Model;

use \OCFram\Manager;
use \Entity\Scenario;

class ScenariosManagerPDO extends Manager
{
    /**
     * @param Scenario $scenario
     * @return mixed
     */
    public function add(Scenario $scenario)
    {
        //On vérifie si le nom existe deja dans scenario_corresp
        $q = $this->dao->prepare('SELECT nom FROM scenario_corresp WHERE nom=:nom');
        $q->bindValue(':nom', $scenario->nom());
        $q->execute();
        $nom = $q->fetchColumn();
        $q->closeCursor();

        echo $nom;
        echo $scenario->nom();

        if ($nom != $scenario->nom()) {


            //Ajout du nom dans scenario_corresp
            $q = $this->dao->prepare('INSERT INTO scenario_corresp (nom) VALUES (:nom)');
            $q->bindValue(':nom', $scenario->nom());
            $success = $q->execute();
            $q->closeCursor();
        } else {
            echo 'ce nom existe deja';

        }

        //On ramene l'id de scenario_corresp en fonction du nom
        $q = $this->dao->prepare('SELECT id FROM scenario_corresp WHERE nom=:nom');
        $q->bindValue(':nom', $scenario->nom());
        $q->execute();
        $scenario->setScenarioid((int)$q->fetchColumn());
        $q->closeCursor();

        //On persiste dans scenario l'objet scenario
        $q = $this->dao->prepare('INSERT INTO scenario (scenarioid,actionneurid,etat) VALUES (:scenarioid,:actionneurid,:etat)');
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
     * @param $id
     */
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM scenario_corresp WHERE id = ' . (int)$id);
        $this->dao->exec('DELETE FROM scenario WHERE scenarioid = ' . (int)$id);
    }

    /**
     * @param $id
     */
    public function deleteItem($id)
    {
        $this->dao->exec('DELETE FROM scenario WHERE id = ' . (int)$id);
    }


    /**
     * @param $id
     */
    public function getUnique($id)
    {

    }

    /**
     * @return mixed
     */
    public function getList()
    {
        $sql = 'SELECT  scenario.id,scenario.scenarioid,scenario_corresp.nom,scenario.actionneurid,scenario.etat
			FROM scenario_corresp 
			INNER JOIN scenario
			ON scenario.scenarioid = scenario_corresp.id	   
	';

        $q = $this->dao->prepare($sql);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Scenario');

        $result = $q->fetchAll();
        $q->closeCursor();

        return $result;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getScenario($id)
    {
        $sql = 'SELECT scenario.scenarioid,scenario_corresp.nom,scenario.actionneurid,scenario.etat
			FROM scenario_corresp 
			INNER JOIN scenario
			ON scenario.scenarioid = scenario_corresp.id	   
		    WHERE scenario.scenarioid=:id
		    GROUP BY scenario.scenarioid
	';

        $q = $this->dao->prepare($sql);
        $q->bindParam(':id', $id);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Scenario');

        $result = $q->fetch();
        $q->closeCursor();

        return $result;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getName($id)
    {
        $sql = 'SELECT scenario_corresp.nom as nom   
			   FROM scenario_corresp
			   INNER JOIN scenario
	          ON scenario.scenarioid = scenario_corresp.id
	          WHERE scenario.id=:id
	';

        $q = $this->dao->prepare($sql);
        $q->bindParam(':id', $id);
        $q->execute();
        $result = $q->fetchColumn();
        $q->closeCursor();

        return $result;

    }

    /**
     * @param Scenario $scenario
     */
    public function update(Scenario $scenario)
    {

        $q = $this->dao->prepare('UPDATE scenario_corresp SET nom = :nom WHERE id = :id');
        if ($q) {
            $q->bindValue(':id', $scenario->id());
            $q->bindValue(':nom', $scenario->nom());
            $q->execute();
            $q->closeCursor();
        } else {
            print_r($this->dao->errorInfo());
        }
    }

    /**
     * @param Scenario $scenario
     */
    public function updateItem(Scenario $scenario)
    {
        $q = $this->dao->prepare('UPDATE scenario SET scenarioid = :scenarioid, actionneurid = :actionneurid, etat = :etat WHERE id = :id');

        if ($q) {
            $q->bindValue(':id', $scenario->id());
            $q->bindValue(':scenarioid', $scenario->scenarioid());
            $q->bindValue(':actionneurid', $scenario->actionneurid());
            $q->bindValue(':etat', $scenario->etat());
            $q->execute();
            $q->closeCursor();
        } else {
            print_r($this->dao->errorInfo());
        }
    }
}
