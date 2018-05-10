<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Actionneur;
use \Debug\Log;

class ActionneursManagerPDO extends ManagerPDO{

  protected $tableName="actionneurs";

  public function getList($categorie=""){

  	$sql = 'SELECT * FROM actionneurs';

  	if($categorie!=""){$sql.=' WHERE categorie = :categorie';}

  	$q = $this->dao->prepare($sql);

  	if($categorie!=""){$q->bindParam(':categorie',$categorie);}

  	$q->execute();
     	$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Actionneur');

  	$listeActionneur = $q->fetchAll();

  	$q->closeCursor();

  	return $listeActionneur;
  }


  
}


