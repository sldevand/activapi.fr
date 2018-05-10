<?php

namespace SFram;

class CurlManage{

	protected $url;
	protected $options=[
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER =>["cache-control: no-cache"]
		  ];
	private $response;
	private $err;

	public function __construct($url){
		$this->setUrl($url);
	}

	public function execute(){
		$curl = curl_init();
		curl_setopt_array($curl, $this->options);
		curl_setopt($curl,CURLOPT_URL,$this->url);
		$this->response = curl_exec($curl);
    		header("Access-Control-Allow-Origin: *");
		$this->err = curl_error($curl);
		curl_close($curl);
	}

	//GETTERS
	public function url(){return $this->url;}
	public function options(){return $this->options;}
	public function response(){return $this->response;}
	public function err(){return $this->err;}

	//SETTERS
	public function setUrl($url){
			 $this->url = $url;
	}

	public function setOptions($options){
		if(!empty($options)&& is_array($options)){
			$this->options=$options;
		}else{

			throw new Exception("options is not an array");
		}
	}
}
