<?php
namespace Model;

use \OCFram\Manager;
use \Entity\ThermostatMode;
use \Debug\Log;

class ThermostatModesManagerPDO extends Manager{
 
  public function count(){
    return $this->dao->query('SELECT COUNT(*) FROM thermostat_modes')->fetchColumn();
  }

  public function delete($id){
    $this->dao->exec('DELETE FROM thermostat_modes WHERE id = '.(int) $id);
  } 

  public function getList(){

    $sql = 'SELECT * FROM thermostat_modes';
    $q = $this->dao->prepare($sql);
    $q->execute();
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
    $thermostatModes = $q->fetchAll();
    $q->closeCursor();
    
    return $thermostatModes;
  }

  public function getUnique($id)
  {
    $sql = 'SELECT * FROM thermostat_modes WHERE id = :id'; 

    $thermostatMode=null;

    $q = $this->dao->prepare($sql);
    if($q){
      $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
      $q->execute();
      $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
      $thermostatMode = $q->fetch();
      $q->closeCursor();     

    }else{

        echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo(),-1);
      
    }
 
    return $thermostatMode;
  }

  public function add(ThermostatMode $thermostatMode){

    $sql = 'INSERT INTO thermostat_modes (nom,consigne,delta) 
              VALUES (:nom,:consigne,:delta)';
    $q = $this->dao->prepare($sql);
 
    if($q){      
      $q->bindValue(':consigne',$thermostatMode->consigne()); 
      $q->bindValue(':nom',$thermostatMode->nom());
      $q->bindValue(':delta',$thermostatMode->delta());  

      $q->execute();
      $q->closeCursor();
    }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( Log::d($this->dao->errorInfo()));
    }
  }

  public function modify(ThermostatMode $thermostatMode){

    $sql = 'UPDATE thermostat_modes 
              SET             
              nom=:nom,
              consigne=:consigne,
              delta=:delta                 
              WHERE id=:id';

      $q = $this->dao->prepare($sql);
      
      if($q){
        $q->bindValue(':id',$thermostatMode->id());     
        $q->bindValue(':consigne',$thermostatMode->consigne()); 
        $q->bindValue(':nom',$thermostatMode->nom());
        $q->bindValue(':delta',$thermostatMode->delta());  

        $q->execute();
        $q->closeCursor();
      }else{     
        echo "\nPDO::errorInfo():\n";
        throw new \RuntimeException( $this->dao->errorInfo());
    }
  
  }

  public function save(ThermostatMode $thermostatMode){

    if ($thermostatMode->isValid())
    {     
      $thermostatMode->isNew() ? $this->add($thermostatMode) : $this->modify($thermostatMode);
    }
    else
    {
      throw new \RuntimeException('Le thermostatMode doit être validé pour être enregistré');
    }
  
  }
}


