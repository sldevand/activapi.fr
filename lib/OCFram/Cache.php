<?php
namespace OCFram;

use \DateTime;
use \DateTimeZone;
use \DateInterval;

class Cache extends ApplicationComponent{

	protected $timeStamp;
	protected $fileName;
	protected $fileArray=[];

	//TimeStamp Management Methods
	public function makeTimeStamp(){

		$dateTime = new DateTime('NOW',new DateTimeZone('Europe/Paris'));
		$expiration = $this->app()->config()->get('cacheExpiration');

		$dateTime->add(DateInterval::createFromDateString($expiration));
		$this->timeStamp= $dateTime->getTimestamp();

	}

	public function getTimeStamp(){

		if(!is_null($this->fileArray) && !empty($this->fileArray)){
			$this->timeStamp=$this->fileArray[0];

			return true;
		}else{
			return false;
		}
	}

	public function isExpired(){

		$dateTime = new DateTime('NOW',new DateTimeZone('Europe/Paris'));
		$now = $dateTime->getTimestamp();
		$this->getTimeStamp();
		if((int)$this->timeStamp-(int)$now <0){
			$this->deleteFile();
			return true;
		}else{
			return false;
		}
	}

	//File Management Methods

	public function exists(){
		return file_exists($this->fileName);
	}

	public function getFile(){
		$this->fileArray = file($this->fileName,FILE_SKIP_EMPTY_LINES);
	}

	public function deleteFile(){
 		 if($this->exists()){return unlink($this->fileName);}
                else{return false;}

	}

	//Data Management Methods
	public function getData($file){

		$this->setDataPath($file);
		if($this->exists()){$this->getFile();}
		else{return false;}

		 if($this->isExpired()){ return false;}
			$fileArray=$this->fileArray[1];
			$data=unserialize($fileArray);


			if(!empty($data) || !is_null($data)){
				return $data;
			}else { return false;}
	}

	public function saveData($file,array $entities){

		$this->setDataPath($file);

		$this->makeTimeStamp();

		if($this->exists()){
			file_put_contents($this->fileName,$this->timeStamp().PHP_EOL);
			file_put_contents($this->fileName, serialize($entities),FILE_APPEND);
		}
	}

	public function setDataPath($file){

		$this->fileName=$this->app()->config()->get('cache').'datas'.'/'
						.$file;
	}

	//View Management Methods
	public function getView($controller){

		$this->setViewPath($controller);
		$content='';

		if($this->exists()){$this->getFile();}
		else{return false;}

		 if($this->isExpired()){ return false;}

			foreach($this->fileArray as $key => $line){
				if($key>0){
					$content.=$line;
				}
			}

			if(!empty($content)){
				$page =  new Page($this->app());
				$page->setContentCache( $content);
				return $page;
			}else { return false;}
	}

	public function saveView($controller,$html){

		$this->setViewPath($controller);
		$this->makeTimeStamp();
		file_put_contents($this->fileName,$this->timeStamp().PHP_EOL);
		file_put_contents($this->fileName, $html,FILE_APPEND);
	}

	public function setViewPath($controller){
		$this->fileName=$this->app()->config()->get('cache').'views'.'/'.
						$this->app()->name().'_'.
						$controller->module().'_'.
						$controller->action().'-'.
						$controller->viewId();
	}

	//GETTERS
	public function timeStamp() {
		return $this->timeStamp;
	}

	public function datas() {
		return $this->datas;
	}

	public function fileName() {
		return $this->fileName;
	}

	public function fileArray() {
		return $this->fileArray;
	}

	//SETTERS
	public function setTimeStamp($timeStamp) {
		$this->timeStamp=$timeStamp;
	}

	public function setDatas(array $datas) {

		if(!empty($datas) && is_array($datas)){
			$this->datas=$datas;
		}
	}

	public function setFileName($fileName) {
		if(!empty($fileName) && is_string($fileName)){
			$this->fileName=$fileName;
		}
	}

	public function setFileArray(array $fileArray) {

		if(!empty($fileArray) && is_array($fileArray)){
			$this->fileArray=$fileArray;
		}
	}

}
