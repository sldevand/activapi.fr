<?php

namespace Model;

use \OCFram\Manager;
use \OCFram\Entity;
use \Debug\Log;


class ManagerPDO extends Manager{

	//Just override the $tableName in inherited class.
	protected $tableName;
	protected $entity;

	public function save(Entity $entity){       
		if ($entity->isValid()) {     
		  $entity->isNew() ? $this->add($entity) : $this->update($entity);
		} else {

		  throw new \RuntimeException($entity->erreurs()["notValid"]);
		}
	}

	public function count(){

		$sql = "SELECT COUNT(*) FROM $this->tableName";

		$q = $this->dao->prepare($sql);
        if(!is_bool($q)){
            $q->execute();
            $result = $q->fetchColumn();
            $q->closeCursor();
            return $result;
        }else{
             throw new \RuntimeException("PDO error : cannot count $this->tableName elements");
        }    	
  	}

	public function delete($id){
		$this->dao->exec("DELETE FROM $this->tableName WHERE id = ".(int) $id);
	}

	public function update(Entity $entity){

		$sql = "UPDATE $this->tableName SET ";
		$properties = $entity->properties();
		$count = count($properties)-2;
		$i=1;

		foreach ($properties as $key => $property) {
		 	if($key!="id" && $key!="erreurs"){

		 		$sql .= $key." = :".$key;
		 		if($i<$count) $sql.=",";
		 		$sql.=" ";
		 	}
		 	$i++;
		} 

		$sql .= "WHERE id = :id"; 

		$q = $this->dao->prepare($sql);
	    if(!is_bool($q)){
			foreach ($properties as $key => $property) {
			
				if($key!="erreurs"){		
					$q->bindValue(':'.$key,$property);
				}
			}

		  	$q->execute();
		  	$q->closeCursor();   
		}else{

			throw new \RuntimeException("PDO error : cannot update $entity");
		}
	}

	public function add(Entity $entity){


		$sql = "INSERT INTO $this->tableName (";
	  	$properties = $entity->properties();
	  	$count = count($properties)-2;
	  	$i=1;

	  	foreach ($properties as $key => $property) {
	  	 	if($key!="id" && $key!="erreurs"){
	  	 		$sql .= $key;
	  	 		if($i<$count) $sql.=",";
	  	 		$sql.=" ";
	  	 	}
	  	 	$i++;
	  	} 

	  	$i=1;
	  	$sql .= ") VALUES (";
		foreach ($properties as $key => $property) {
	  	 	if($key!="id" && $key!="erreurs"){
	  	 		$sql .= ":".$key;
	  	 		if($i<$count) $sql.=",";
	  	 		$sql.=" ";
	  	 	}
	  	 	$i++;
	  	} 
		$sql .= ")";
	 
		$q = $this->dao->prepare($sql);

		if(!is_bool($q)){  
			foreach ($properties as $key => $property) {
			
				if($key!="erreurs" && $key!="id"){		
					$q->bindValue(':'.$key,$property);
				}
			}
			$success = $q->execute();
			$q->closeCursor();
		}else{
			throw new \RuntimeException("PDO error : cannot add $entity");
		}
	
	}

	public function getUnique($id)
	 {

	 	$entityName = "\\Entity\\".ucfirst(substr($this->tableName, 0, -1));  
		
	 	$sql="SELECT * FROM $this->tableName WHERE id = :id"; 

		$q = $this->dao->prepare($sql);
		$q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$q->execute();		

		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $entityName);

		if ($this->entity = $q->fetch()){
		  $q->closeCursor();
		  return $this->entity;
		}else{

			throw new \Exception("PDO error : cannot fetch unique $entity at $id");
		}

		$q->closeCursor();
		return null;
	 }

 	
  	//GETTERS
	public function tableName(){return $this->tableName;}	

	//SETTERS
	public function setTableName($tableName){
		if(!empty($tableName) && is_string($tableName)){
			$this->tableName=$tableName;
		}else{
			throw new Exception("$tableName is not a string or is empty");
		}
	}

}
