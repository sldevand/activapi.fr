<?php
const DEFAULT_APP = 'Frontend';
ini_set('memory_limit', '2048M');
// Si l'application n'est pas valide, on va charger l'application par défaut qui se chargera de générer une erreur 404
if (!isset($_GET['app']) || !file_exists(__DIR__.'/../App/'.$_GET['app'])) $_GET['app'] = DEFAULT_APP;

// On commence par inclure la classe nous permettant d'enregistrer nos autoload
require __DIR__.'/../lib/OCFram/SplClassLoader.php';

// On va ensuite enregistrer les autoloads correspondant à chaque vendor (OCFram, App, Model, etc.)
$ocFramLoader = new SplClassLoader('OCFram', __DIR__.'/../lib');
$ocFramLoader->register();

$sFramLoader = new SplClassLoader('SFram', __DIR__.'/../lib');
$sFramLoader->register();

$appLoader = new SplClassLoader('App', __DIR__.'/..');
$appLoader->register();

$modelLoader = new SplClassLoader('Model', __DIR__.'/../lib/vendors');
$modelLoader->register();

$entityLoader = new SplClassLoader('Entity', __DIR__.'/../lib/vendors');
$entityLoader->register();

$materializeLoader = new SplClassLoader('Materialize', __DIR__.'/../lib/vendors');
$materializeLoader->register();

$osDetectorLoader = new SplClassLoader('OSDetector', __DIR__.'/../lib/vendors');
$osDetectorLoader->register();

$debugLoader = new SplClassLoader('Debug', __DIR__.'/../lib/vendors');
$debugLoader->register();

$formBuilderLoader = new SplClassLoader('FormBuilder', __DIR__.'/../lib/vendors');
$formBuilderLoader->register();

use \OCFram\PDOFactory;
use \SFram\OSDetectorFactory;
OSDetectorFactory::begin();

// Il ne nous suffit plus qu'à déduire le nom de la classe et de l'instancier
$appClass = 'App\\'.$_GET['app'].'\\'.$_GET['app'].'Application';
$app = new $appClass;

$key = OSDetectorFactory::getPdoAddressKey();
PDOFactory::setPdoAddress($app->config()->get($key));

$app->run();