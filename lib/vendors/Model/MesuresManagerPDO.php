<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Mesure;
use \Entity\Sensor;

class MesuresManagerPDO extends ManagerPDO{


  protected $tableName="mesures";

  public function addWithSensorId(Mesure $mesure)
  {
  	$q = $this->dao->prepare('SELECT id FROM sensors WHERE radioid = :radioid');
  	$q->bindValue(':radioid',$mesure->id_sensor());
  	$q->execute();
  	$id = $q->fetchColumn();
  	$q->closeCursor();

  	$q = $this->dao->prepare('INSERT INTO mesures (id_sensor, temperature, hygrometrie, horodatage) VALUES (:id_sensor,:temperature,:hygrometrie,DateTime("now","localtime"))');
  	$q->bindValue(':id_sensor',$id);
  	$q->bindValue(':temperature',$mesure->temperature());
  	$q->bindValue(':hygrometrie',$mesure->hygrometrie());
  	$success = $q->execute();
  	$q->closeCursor();

	return $success;
  }

  public function getList($debut = 0, $limite = 50)
  {
    $sql = 'SELECT s.radioid id_sensor,s.nom, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id
			ORDER BY m.horodatage DESC';

    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }

    $q = $this->dao->query($sql);

    if($q){
      $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');
      $listeMesure = $q->fetchAll();
      $q->closeCursor();
    }else{

      echo "\nPDO::errorInfo():\n";
      print_r( $this->dao->errorInfo());
    }
    return $listeMesure;
}

  public function getSensorList($sensor,$dateMin,$dateMax)
  {
	$dateMin.=' 00:00:00';
	$dateMax.=' 00:00:00';

	 $sql = 'SELECT s.radioid id_sensor,s.nom nom, s.id, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id
			AND s.radioid= :id_sensor
			AND horodatage >= :dateMin
			AND horodatage <= :dateMax
			ORDER BY horodatage ASC';
    	$q = $this->dao->prepare($sql);
	$q->bindParam(':id_sensor',$sensor);
	$q->bindParam(':dateMin',$dateMin);
	$q->bindParam(':dateMax',$dateMax);

	$q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');

    $listeMesure = $q->fetchAll();

    $q->closeCursor();

    return $listeMesure;
  }

  public function getSensor($radioId){
    $q = $this->dao->prepare('SELECT * FROM sensors WHERE radioid = :radioid');
    $q->bindParam(':radioid',$radioId);
    $q->execute();
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');

	 	$sensor = $q->fetchAll();
    $q->closeCursor();
		return $sensor;
  }

  public function getSensors($categorie){

  	$sql = "SELECT * FROM sensors";
  	if($categorie!="" && $categorie!=null){
  		$sql.=" WHERE categorie=:categorie";
  	}

  	$q = $this->dao->prepare($sql);
  	if($categorie!="" && $categorie!=null){
  		$q->bindParam(":categorie",$categorie);
  	}
  	$q->execute();
  	$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');

    $sensors = $q->fetchAll();
    $q->closeCursor();
    return $sensors;
 }

  public function sensorActivityUpdate($sensorEntity,$actif){

  	if($actif==1){
  		$q = $this->dao->prepare('UPDATE sensors SET actif = :actif,  releve=DateTime("now","localtime"), valeur1=:valeur1, valeur2=:valeur2 WHERE radioid = :radioid');
    		$q->bindValue(':valeur1',$sensorEntity->valeur1());
        $q->bindValue(':valeur2',$sensorEntity->valeur2());
  	}else{
  		$q = $this->dao->prepare('UPDATE sensors SET actif = :actif WHERE radioid = :radioid');
  	}
  	$q->bindParam(':actif',$actif);
  	$q->bindValue(':radioid',$sensorEntity->radioid());
          $q->execute();
  	$q->closeCursor();
    }

}


