<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Sensor;
use \Debug\Log;

class SensorsManagerPDO extends ManagerPDO{

    protected $tableName='sensors';
    protected $entity;

   
  public function getList($categorie=""){

  	$sql = 'SELECT * FROM sensors';

  	if($categorie!=""){$sql.=' WHERE categorie = :categorie';}

  	$q = $this->dao->prepare($sql);

  	if($categorie!=""){$q->bindParam(':categorie',$categorie);}

  	$q->execute();
     	$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');

  	$listeSensor = $q->fetchAll();

  	$q->closeCursor();

  	return $listeSensor;
  }

}


