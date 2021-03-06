<?php

include_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

if ($_ENV['DEBUG']) {
    ini_set('display_errors', true);
}

ini_set('memory_limit', '2048M');
define('ROOT', $_ENV['ROOT_URI']);
define('ROOT_API', $_ENV['ROOT_API_URI']);

const SITE = ROOT . '/';
const CSS = SITE . 'css';
const JS = SITE . 'js';
const DIST = SITE . 'dist';
const IMG = SITE . 'img';
const NODE_MODULES = SITE . 'node_modules';
const JS_UTILS = JS . '/utils';
const DEFAULT_APP = 'Frontend';

const APP = __DIR__ . '/';
const LIB = __DIR__ . '/lib';
const VENDORS = LIB . '/vendors';
const TESTS = __DIR__ . '/Tests';

const FRONTEND = __DIR__ . '/App/Frontend';
const MODULES = FRONTEND . '/Modules';
const TEMPLATES = FRONTEND . '/Templates';
const BLOCK = TEMPLATES . '/Block';

const BACKEND = __DIR__ . '/App/Backend';
const BACKEND_TEMPLATES = BACKEND . '/Templates';

date_default_timezone_set('Europe/Paris');
setlocale (LC_TIME, 'fr_FR.utf8','fra');