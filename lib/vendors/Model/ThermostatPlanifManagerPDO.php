<?php
namespace Model;

use \OCFram\Manager;
use \Entity\ThermostatPlanif;
use \Entity\ThermostatPlanifNom;
use \Entity\ThermostatMode;
use \Debug\Log;

class ThermostatPlanifManagerPDO extends Manager{
 
  public function count(){
    return $this->dao->query('SELECT COUNT(*) FROM thermostat_planif')->fetchColumn();
  }

   public function countPlanifs(){
    return $this->dao->query('SELECT COUNT(*) FROM thermostat_corresp')->fetchColumn();
  }

  public function delete($id){
    $this->dao->exec('DELETE FROM thermostat_corresp WHERE id = '.(int) $id);
    $this->dao->exec('DELETE FROM thermostat_planif WHERE nomid = '.(int) $id);
  } 

  public function getListArray(){

   $liste=$this->getAllPlanifs();

    $listeTab=[];

    foreach($liste as $value){
      $nomid = (int)$value["id"];
      $listeTab[]=$this->getList($nomid);    

    }
    return $listeTab;
  }
    
  public function getList($id){

    $sql = 'SELECT * FROM thermostat_planif';	

    if(!is_null($id) && !empty($id)){
      $sql.=' WHERE nomid=:nomid';}

  	$q = $this->dao->prepare($sql);

    if(!is_null($id) && !empty($id)){
      $q->bindValue(':nomid',$id);
    }
  	$q->execute();
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanif');
    $listeThermostatPlanif = $q->fetchAll();
    $q->closeCursor();


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

  public function getUnique($id)
  {
    $sql = 'SELECT * FROM thermostat_planif WHERE id = :id'; 


    $q = $this->dao->prepare($sql);
    if($q){
      $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
      $q->execute();
      $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanif');
      $thermostatPlanif = $q->fetch();
      $q->closeCursor();

      if($thermostatPlanif->modeid()){
        $thermostatPlanif->setMode( $this->getMode($thermostatPlanif->modeid()));
      }else{
        throw new \RuntimeException('modeid is null!');
      }

      if($thermostatPlanif->defaultModeid()){
          $thermostatPlanif->setDefaultMode( $this->getMode($thermostatPlanif->defaultModeid()));
      }else{
        throw new \RuntimeException('defaultModeid is null!');
      }
   

    }else{

        echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo(),-1);
      
    }
 
    return $thermostatPlanif;
  }

  public function save(ThermostatPlanif $thermostatPlanif){  

    if ($thermostatPlanif->isValid())
    {     
      $thermostatPlanif->isNew() ? $this->addPlanifTable($thermostatPlanif->nom()) : $this->modify($thermostatPlanif);
    }
    else
    {
      throw new \RuntimeException('Le thermostatPlanif doit être valide pour être enregistré');
    }
  
  }

  public function modify(ThermostatPlanif $thermostatPlanif){

  
    $sql = 'UPDATE thermostat_planif 
            SET             
            modeid=:modeid,
            defaultModeid=:defaultModeid,
            heure1Start=:heure1Start,
            heure1Stop=:heure1Stop,
            heure2Start=:heure2Start,
            heure2Stop=:heure2Stop           
            WHERE id=:id';

    $q = $this->dao->prepare($sql);

    if($q){
      $q->bindValue(':id',$thermostatPlanif->id());       
      $q->bindValue(':modeid',$thermostatPlanif->modeid());
      $q->bindValue(':defaultModeid',$thermostatPlanif->defaultModeid());
      $q->bindValue(':heure1Start',$thermostatPlanif->heure1Start());
      $q->bindValue(':heure1Stop',$thermostatPlanif->heure1Stop());
      $q->bindValue(':heure2Start',$thermostatPlanif->heure2Start());
      $q->bindValue(':heure2Stop',$thermostatPlanif->heure2Stop()); 

      $q->execute();
      $q->closeCursor();
    }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo());
    }
  } 
  

   public function add(ThermostatPlanif $thermostatPlanif){

  
    $sql = 'INSERT INTO thermostat_planif 
            (jour,modeid,defaultModeid,heure1Start,heure1Stop,heure2Start,heure2Stop,nomid) 
            VALUES          
            (:jour,:modeid,:defaultModeid,:heure1Start,:heure1Stop,:heure2Start,:heure2Stop,:nomid) ';

    $q = $this->dao->prepare($sql);

    if($q){    
      $q->bindValue(':jour',$thermostatPlanif->jour());
      $q->bindValue(':modeid',$thermostatPlanif->modeid());
      $q->bindValue(':defaultModeid',$thermostatPlanif->defaultModeid());
      $q->bindValue(':heure1Start',$thermostatPlanif->heure1Start());
      $q->bindValue(':heure1Stop',$thermostatPlanif->heure1Stop());
      $q->bindValue(':heure2Start',$thermostatPlanif->heure2Start());
      $q->bindValue(':heure2Stop',$thermostatPlanif->heure2Stop()); 
      $q->bindValue(':nomid',$thermostatPlanif->nomid());

      $q->execute();
      $q->closeCursor();
    }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo());
    }
  } 



  public function addPlanifTable($nom){

    $nomid=(int)$this->addNom($nom);
    if($nomid>0){
      $thermostatPlanifs=[];
      for($jour=1;$jour<8;$jour++){

        $thermostatPlanif = new ThermostatPlanif([         
          "jour"=>$jour,
          "modeid"=>"1",
          "defaultModeid"=>"3",
          "heure1Start"=>"",
          "heure1Stop"=>"",
          "heure2Start"=>"",
          "heure2Stop"=>"",
          "nomid"=>$nomid
        ]);

        $this->add($thermostatPlanif);     
      }

        return $nomid;
      
    }else{
      return 0;

    }
    



  }

   public function getMode($id){

      $sql='SELECT * FROM thermostat_modes WHERE id = :id';

      $q = $this->dao->prepare($sql);
      $q->bindValue(':id',$id);
      $q->execute();
      $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
      $mode = $q->fetch();
      $q->closeCursor();
      return $mode;

   }

   public function getModes(){

      $sql='SELECT * FROM thermostat_modes';

      $q = $this->dao->prepare($sql);    
      $q->execute();
      $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
      $modes= $q->fetchAll();
      $q->closeCursor();
      return $modes;

   }

   public function addNom($name){

    $noms = $this->getNoms();

    foreach($noms as $key=>$nom){
      if($nom->nom()==$name){
         return "Ce Nom existe déjà!";  
      }   
    }

    $sql='INSERT INTO thermostat_corresp (nom) VALUES (:nom)';
    $q = $this->dao->prepare($sql);    
    if($q){
      
      $q->bindValue(':nom',$name);
      $q->execute();

      $lastId=$this->dao->lastInsertId();
    }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo());
    }
    return $lastId;
   }
  
    public function getNom($id){

      $sql='SELECT * FROM thermostat_corresp WHERE id = :id';

      $q = $this->dao->prepare($sql);
      if($q){
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanifNom');
        $nom = $q->fetch();
        $q->closeCursor();
      }else{     
        echo "\nPDO::errorInfo():\n";
        throw new \RuntimeException( $this->dao->errorInfo());
      }
      return $nom;
   }

   public function getNoms(){

      $sql='SELECT * FROM thermostat_corresp';

      $q = $this->dao->prepare($sql);      

      if($q){
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatPlanifNom');
        $noms= $q->fetchAll();
        $q->closeCursor();
      }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo());
    }
      return $noms;
   }

    public function getAllPlanifs(){

     $sql='SELECT * FROM thermostat_corresp';

      $q = $this->dao->prepare($sql);      

      if($q){
        $q->execute();
        $planifs= $q->fetchAll();
        $q->closeCursor();
      }else{     
      echo "\nPDO::errorInfo():\n";
      throw new \RuntimeException( $this->dao->errorInfo());
    }

      return $planifs;


   }




}


