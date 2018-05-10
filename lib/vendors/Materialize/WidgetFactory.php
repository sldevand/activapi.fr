<?php

namespace Materialize;
use \Debug\Log;

class WidgetFactory{

	public static function makeCard($domId,$cardTitle,$cardContent){

	    $cardOpt=[
	        'id'=>$domId,
	        'bgColor'=>'primaryLightColor',
	        'textColor'=>'textOnPrimaryColor',
	        'title'=>$cardTitle];

	    $card=new Card($cardOpt);  
	    $card->setContent($cardContent);

	    return $card;
  	}

  	public static function makeTable($domId,$rawDatas,$jsonencode=true,$hideColumns=[]){

	   	if($jsonencode){ 
	   		$datas=json_decode(json_encode($rawDatas),TRUE);
	   	}else{
	   		$datas=(array) $rawDatas;
	   	} 

	    $tableDatas=[];
	    $headers=[];
	
	    foreach ($datas[0] as $key => $data) {
	      $headers[]=$key;   

	    } 

	    foreach ($datas as $key => $data){   	     
	      $tableDatas[]=$data;
	    }  

	    return new Table([
	      'id'=>'table'.$domId,
	      'datas'=> $tableDatas,
	      'headers'=>$headers,
	      'hideColumns'=>$hideColumns
	    ]);	  
	}	
}
