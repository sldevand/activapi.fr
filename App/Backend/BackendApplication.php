<?php
namespace App\Backend;

use \OCFram\Application;
use \OCFram\Cache;

class BackendApplication extends Application
{
  public function __construct()
  {
    parent::__construct();

    $this->name = 'Backend';
  }

  public function run(){    
    
    $controller = $this->getController();
    $controller->execute();
    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->sendJSON();
  }
}
