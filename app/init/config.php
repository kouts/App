<?php

// Put config files to the config
Config::set('app', include(APP_DIR.'/config/app.php'));
Config::set('db', include(APP_DIR.'/config/db.php'));
Config::set('languages', include(APP_DIR.'/config/languages.php'));
Config::set('twig', include(APP_DIR.'/config/twig.php'));
Config::set('proxy', include(APP_DIR.'/config/proxy.php'));