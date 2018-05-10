<?php
namespace OCFram;

use \DateTime;
use \DateTimeZone;
use \DateInterval;


class DateFactory{
	
	static public $days=[1=>"Lundi",2=>"Mardi",3=>"Mercredi",4=>"Jeudi",5=>"Vendredi",6=>"Samedi",7=>"Dimanche"];
	
	public function prepareHourFromFile($hourStr){

		$hourStr = explode(' ',$hourStr);
		$timeStr = explode(':',$hourStr[0]);
		
		$heure=$timeStr[0];
		$minute=$timeStr[1];	
		$seconde=$timeStr[2];
		
		if( strlen($heure.':'.$minute.':'.$seconde)<8){
			if(strlen($heure)==1) $heure='0'.$heure;
			if(strlen($minute)==1) $minute='0'.$minute;
			if(strlen($seconde)==1) $seconde='0'.$seconde;
		}	
		return $heure.':'.$minute.':'.$seconde;
	}

	public function createDate($dateStr){
		$date = date_create_from_format('d-m-Y H:i:s',$dateStr);
		if(!is_bool($date)){
			$dateFormatted=date_format($date, '20y-m-d H:i:s');	
		}else{
			 $dateFormatted = -1;
		}
		return $dateFormatted;
	}

	static public function diffMinutes($date1,$date2){
		return $minutes = $date1->diff($date2)->i+ $date1->diff($date2)->h*60;

	}
	static public function diffMinutesFromStr($dateStr1,$dateStr2){
		$date1= new DateTime($dateStr1,new DateTimeZone('Europe/Paris'));
		$date2= new DateTime($dateStr2,new DateTimeZone('Europe/Paris'));

		return self::diffMinutes($date1,$date2);
	}

	static public function createDateFromStr($dateStr){
		return new DateTime($dateStr,new DateTimeZone('Europe/Paris'));
	}

	static public function todayToString(){
		$now =  new DateTime("now",new DateTimeZone('Europe/Paris'));
		return $now->format("Y-m-d");

	}

	static public function toFrDate($dateStr){
		$date = new DateTime($dateStr,new DateTimeZone('Europe/Paris'));
		return $date->format('d/m/Y');
	}

	static public function toStrDay($dayNbr){
		$strDay=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
		if(is_numeric($dayNbr)){
			$dayNbr--;
			if($dayNbr<0 || $dayNbr>6){return null;}
			return $strDay[$dayNbr];
		}else{
			return null;
		}	
	}
}
