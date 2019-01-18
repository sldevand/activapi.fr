<?php
require_once LIB . '/OCFram/SplClassLoader.php';

$ocFramLoader = new SplClassLoader('OCFram', LIB);
$ocFramLoader->register();

$sFramLoader = new SplClassLoader('SFram', LIB);
$sFramLoader->register();

$appLoader = new SplClassLoader('App', __DIR__ . '/');
$appLoader->register();

$modelLoader = new SplClassLoader('Model', VENDORS);
$modelLoader->register();

$entityLoader = new SplClassLoader('Entity', VENDORS);
$entityLoader->register();

$materializeLoader = new SplClassLoader('Materialize', VENDORS);
$materializeLoader->register();

$osDetectorLoader = new SplClassLoader('OSDetector', VENDORS);
$osDetectorLoader->register();

$debugLoader = new SplClassLoader('Debug', VENDORS);
$debugLoader->register();

$formBuilderLoader = new SplClassLoader('FormBuilder', VENDORS);
$formBuilderLoader->register();