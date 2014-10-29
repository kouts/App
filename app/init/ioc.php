<?php

// Register the file class
App::register('File', '\libs\File\File');

// Register the language class
App::register('Lang', '\libs\Language\Language', array(Config::get('languages')));

// Register the translations class
App::register('Translation', '\libs\Translation\Translation');

// Register the redirect class
App::register('redirect', '\libs\Redirect\Redirect', array(
    App::make('config'),
    App::make('request'),
    App::make('response'),
    App::make('Lang')
    )
);

// Register the messages class
App::register('Msg', '\libs\Message\Message', array(App::make('response')));

// Register Twig as the view class
App::register('TwigFilesystem', 'Twig_Loader_Filesystem', array(Config::get('twig.templates_dir')));

App::register('view', 'Twig_Environment', array(App::make('TwigFilesystem')), function($twig){
    $twig->setCache(Config::get('twig.cache_dir'));
    $twig->enableAutoReload();
    $twig->addExtension(new Twig_Extension_Escaper('html'));
    $twig->addExtension(new Phive\Twig\Extensions\Deferred\DeferredExtension());
    $twig->addExtension(new views_extensions_App());
    $twig->addGlobal('session', $_SESSION);
    $twig->addGlobal('config', Config::all());
    $twig->addGlobal('css', new ArrayObject());
    $twig->addGlobal('js', new ArrayObject());
    $twig->addGlobal('resources', new ArrayObject());
});

// Register proxy
App::register('Proxy', '\libs\Proxy\Proxy', array(Config::get('proxy.ip'), Config::get('proxy.port'), Config::get('proxy.username'), Config::get('proxy.password')));

// Register the db connection
App::register('Db', '\libs\Db\Db', array(Config::get('db.host'), Config::get('db.dbname'), Config::get('db.user'), Config::get('db.pass')));

// Make the db connection (Idiorm - Paris)
ORM::configure(array(
    'connection_string' => 'mysql:host='.Config::get('db.host').';dbname='.Config::get('db.dbname').'',
    'username' => Config::get('db.user'),
    'password' => Config::get('db.pass'),
    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    'error_mode', PDO::ERRMODE_WARNING
));