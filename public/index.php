<?php

$startTime = microtime(true);

define('BASE_DIR', realpath('../'));
define('APP_DIR', BASE_DIR.'/app');

// Init composer autoloader and add paths
$autoloader = require BASE_DIR.'/vendor/autoload.php';
$autoloader->add('', array(APP_DIR, APP_DIR.'/facades'));

// Set engine as the facade application
Facade::setFacadeApplication(new Engine());

// Require helpers
require APP_DIR.'/libs/helpers.php';

// Require config
require APP_DIR.'/init/config.php';

// Require ioc registrations
require APP_DIR.'/init/ioc.php';

// Require filters
require APP_DIR.'/init/filters.php';

// Require routes
require APP_DIR.'/init/routes.php';

App::fire('start');

?>