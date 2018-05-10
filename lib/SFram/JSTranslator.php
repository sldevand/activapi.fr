<?php 
namespace SFram;

class JSTranslator{

	protected $phpVars;

	public function __construct($phpVars){
		$this->setPhpVars($phpVars);
	}	

	public function addVar($key,$phpVar){	
		$this->phpVars[$key]=$phpVar;
	}

	public function setPhpVars($phpVars){
		if(is_array($phpVars)){
			foreach ($phpVars as $key=>$value) {
				$this->addVar($key,$value);
			}
		}else{
 			throw new \Exception('phpVars is not an array');
		}
	}

	public function toVars(){

		$jsReturn='<script>';
		
		
		if(!is_null($this->phpVars)){
			foreach ($this->phpVars as $key => $phpVar) {

			$jsReturn.='var '.$key. ' = ';

			if(!empty($phpVar) && !is_null($phpVar)){				

				if(is_string($phpVar) || is_numeric($phpVar)){

					$jsReturn.='"'.$phpVar.'";';

				}elseif(is_array($phpVar)){

						$jsReturn.="[];";
						foreach ($phpVar as $value) {
							$jsReturn.=$key.'.push("'.$value.'");';			
						}
				}

			}else{				
				$jsReturn.='"'.'";';
			}
			}
		}else{
			throw new \Exception('$this->phpVars is null!');
		}
		$jsReturn.='</script>';

		
		return $jsReturn;

	}




}