<?php
namespace SFram;
use \OSDetector\Detector;

class OSDetectorFactory 
{
    public static $detector;

    public static function begin(){
     	self::$detector = new Detector();
    }

    public static function getKernelName(){		
		return self::$detector->getKernelName();
	}	

	public static function isUnixLike(){
		return self::$detector->isUnixLike();
	}

	public static function isWindowsLike(){
		return self::$detector->isWindowsLike();
	}

	public static function getApiAddressKey(){

		$key='apiLinBaseAddress';

		if (OSDetectorFactory::isUnixLike()) {
    		$key='apiLinBaseAddress';
		} else if (OSDetectorFactory::isWindowsLike()) {
    		$key='apiWinBaseAddress';
		} else {
    		$key='apiLinBaseAddress';
		}
		return $key;
	}

	public static function getPdoAddressKey(){
		$key='pdoLinAddress';

		if (OSDetectorFactory::isUnixLike()) {
		    $key='pdoLinAddress';

		} else if (OSDetectorFactory::isWindowsLike()) {
		    $key='pdoWinAddress';
		} else {
		    $key='pdoLinAddress';
		}
		return $key;
	}
}

