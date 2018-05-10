<?php

namespace SFram;

use \ReflectionClass;
use \ReflectionObject;
use \Debug\Log;


class AnnotationManager{

	
	public static function getClass($entity){
		return new ReflectionObject($entity);	
	}

	public static function getClassName($entity){
		return self::getClass($entity)->name;	
	}

	public static function getAnnotations($entity){
		$annotations["class"]=self::getClassAnnotation($entity);

		$methods = $entity->methods();
		$properties = $entity->properties();

		foreach ($methods as $key => $method) {
			if(self::getMethodAnnotation($entity,$method)){
				$annotations["methods"][$key] = self::getMethodAnnotation($entity,$key);
			}
		}

		foreach ($properties as $key => $property) {
			$annotations["properties"][$key] = self::getPropertyAnnotation($entity,$key);
		} 	

  		return $annotations;
	}

	public static function getClassAnnotation($entity){
		$entityClassName = self::getClassName($entity);
		
		$annotation=null;		
		if(isset($entityClassName) && !empty($entityClassName)){

			$docComment = (new ReflectionClass($entityClassName))->getdoccomment();	
		
			$pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*);#";
			preg_match_all($pattern, $docComment, $annotation, PREG_PATTERN_ORDER);
		}

		return $annotation[1];

	}

	public static function getMethodAnnotation($entity,$method){

		$entityClassName = self::getClassName($entity);
		
		$annotation=null;		
		if(isset($entityClassName) && !empty($entityClassName)){
			$docComment = (new ReflectionClass($entityClassName))->getMethod($method)->getdoccomment();		

			if(isset($docComment) && !empty($docComment)){
				$pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*);#";
				preg_match_all($pattern, $docComment, $annotation, PREG_PATTERN_ORDER);
			}
		}

		return $annotation[1];

	}

	public static function getPropertyAnnotation($entity,$property){

		$entityClassName = self::getClassName($entity);
		
		$annotation=null;		
		if(isset($entityClassName) && !empty($entityClassName)){
			$docComment = (new ReflectionClass($entityClassName))->getProperty($property)->getdoccomment();		

			if(isset($docComment) && !empty($docComment)){
				$pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*);#";
				preg_match_all($pattern, $docComment, $annotation, PREG_PATTERN_ORDER);
			}
		}

		return $annotation[1];
	}

	

}