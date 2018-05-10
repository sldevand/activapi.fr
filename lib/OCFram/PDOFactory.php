<?php
namespace OCFram;

use \PDO;

class PDOFactory
{
  public static $lastUsedConnexion='';	
  public static $pdoAdress='';
	
  public static function getMysqlConnexion() {
   
	try{
		$db = new PDO('mysql:host=localhost;dbname=listes;charset=utf8', 'root', '');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PdoException $e){
		die($e->getMessage());
	}	
	
	self::$lastUsedConnexion='mysql';
	
    return $db;
  }
  
  public static function getSqliteConnexion() {
   
	try{
	

		$db = new PDO(self::$pdoAdress);		 
		 //$db = new PDO('sqlite:C:\wamp64\www\database\releves.db');
		//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PdoException $e){
		die($e->getMessage());
	}	
	
	self::$lastUsedConnexion='sqlite';
    
    return $db;
  }

  public static function setPdoAddress($address){

		self::$pdoAdress=$address;
  }

  
  
}
