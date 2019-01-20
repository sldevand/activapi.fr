<?php

include_once __DIR__ . '/vendor/autoload.php';

ini_set('memory_limit', '2048M');
ini_set('display_errors', 1);
const ROOT = '/activapi.fr';
const SITE = ROOT . '/Web/';
const CSS = SITE . 'css';
const JS = SITE . 'js';
const NODE_MODULES = SITE . 'node_modules';
const JS_UTILS = JS . '/utils';
const DIST = SITE . 'dist';
const DEFAULT_APP = 'Frontend';

const APP = __DIR__ . '/';
const LIB = __DIR__ . '/lib';
const VENDORS = LIB . '/vendors';

const FRONTEND =  __DIR__ . '/App/Frontend';
const MODULES =FRONTEND.'/Modules';
const TEMPLATES = FRONTEND.'/Templates';
const BLOCK = TEMPLATES . '/Block';
