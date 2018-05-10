<?php
namespace OCFram;
use \Debug\Log;


abstract class Entity implements \ArrayAccess , \JsonSerializable
{
  use Hydrator;

  protected $erreurs = [],
            $id;

  public function __construct(array $donnees = [])
  {
    if (!empty($donnees))
    {
      $this->hydrate($donnees);
    }
  }

  public function isNew() {
    return empty($this->id);
  }

  public function erreurs() {
    return $this->erreurs;
  }

  public function addErreur($key,$erreur) {
    $this->erreurs[$key]=$erreur;
  }

  public function id() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }

  public function offsetGet($var) {
    if (isset($this->$var) && is_callable([$this, $var])) {
      return $this->$var();
    }
  }

  public function offsetSet($var, $value)
  {
    $method = 'set'.ucfirst($var);

    if (isset($this->$var) && is_callable([$this, $method]))
    {
      $this->$method($value);
    }
  }

  public function offsetExists($var)
  {
    return isset($this->$var) && is_callable([$this, $var]);
  }

  public function offsetUnset($var)
  {
    throw new \Exception('Impossible de supprimer une quelconque valeur');
  }
  
  abstract public function jsonSerialize();

  public function isValid(){

    $properties = get_object_vars($this);  

    foreach ($properties as $key => $property) {
      if($key != "erreurs" && $key!="id"){

        if(!isset($property)){
          $objClass = new \ReflectionObject($this);
          
          $this->erreurs["notValid"]= "in object ".$objClass->name." , ".$key." is not set";
                
          return false;
        }
      } 
    }
   
    return true;
  }

  public function properties(){
    return get_object_vars($this);  
  }

  public function methods(){
    $cl = new \ReflectionObject($this);
    $class = $cl->name;
      $array1 = get_class_methods($class);
    if($parent_class = get_parent_class($class)){
        $array2 = get_class_methods($parent_class);
        $array3 = array_diff($array1, $array2);
    }else{
        $array3 = $array1;
    }
    return($array3);
  }  

  public static function className(){
    $ro = new ReflectionObject($this); 
    return $ro->name; 
  }




}