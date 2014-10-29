<?php

use \flight\core\Dispatcher;

class Facade {

	protected static $app;

	public static function __callStatic($name, $params){
		$instance = static::getInstance();
		return Dispatcher::invokeMethod(array($instance, $name), $params);
	}

	public static function getInstance(){
		$instance = static::$app->make(static::getRegistered());
		if($instance instanceof static::$app){
			return static::$app;
		}
		return $instance;
	}

	public static function getInstanceVar($var){
		$instance = static::getInstance();
		return $instance->{$var};
	}

	public static function setFacadeApplication($app){
		static::$app = $app;
	}

	public static function getFacadeApplication(){
		return static::$app;
	}


}