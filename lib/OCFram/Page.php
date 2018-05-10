<?php
namespace OCFram;

class Page extends ApplicationComponent
{
  protected $contentFile;
  protected $contentCache='';
  protected $vars = [];

  public function addVar($var, $value)
  {
    if (!is_string($var) || is_numeric($var) || empty($var))
    {
      throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
    }

    $this->vars[$var] = $value;
  }

  public function getGeneratedPage()
  {
	if (!empty($this->contentCache)){
		ob_start();
		  echo $this->contentCache;
		return ob_get_clean();
	}else{	  
		if (!file_exists($this->contentFile))
		{
		  throw new \RuntimeException('La vue spécifiée n\'existe pas');
		}

		$user = $this->app->user();

		extract($this->vars);

		ob_start();
		  require $this->contentFile;
		$content = ob_get_clean();

		ob_start();
		  require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
		return ob_get_clean();
	}
  }
  
   public function getGeneratedData()
  {
	  
	$generatedData='haha';
	
	return $generatedData;
  }
  
   public function getGeneratedJSON()
  {
	if (!empty($this->contentCache)){
		ob_start();
		  echo $this->contentCache;
		return ob_get_clean();
	}else{	  
		if (!file_exists($this->contentFile))
		{
		  throw new \RuntimeException('La vue spécifiée n\'existe pas');
		}

		$user = $this->app->user();

		extract($this->vars);

		ob_start();
		  require $this->contentFile;
		$content = ob_get_clean();	

		return $content;
	}
  }
  
 

  public function setContentFile($contentFile)
  {
    if (!is_string($contentFile) || empty($contentFile))
    {
      throw new \InvalidArgumentException('La vue spécifiée est invalide');
    }

    $this->contentFile = $contentFile;
  }
  
   public function setContentCache($contentCache){
	   
	   if (!is_string($contentCache) || empty($contentCache))
		{
		  throw new \InvalidArgumentException('La vue spécifiée est invalide');
		}

    $this->contentCache = $contentCache;
   }
  
  public function contentCache(){return $this->contentCache;}
  
  
 
  
  
}