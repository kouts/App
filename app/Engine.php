<?php

use flight\core\Loader;
use flight\core\Dispatcher;

class Engine {

    /**
     * Class loader.
     *
     * @var object
     */
    protected $loader;

    /**
     * Event dispatcher.
     *
     * @var object
     */
    protected $dispatcher;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->loader = new Loader();
        $this->dispatcher = new Dispatcher();
        $this->init();
    }

    /**
     * Initializes the framework.
     */
    public function init() {
        static $initialized = false;

        if ($initialized) {
            $this->loader->reset();
            $this->dispatcher->reset();
        }

        // Register self!
        $this->loader->register('app', $this);
        // Register the Arr class
        $this->loader->register('arr', '\libs\Support\Arr');
        // Register the Str class
        $this->loader->register('str', '\libs\Support\Str');
        // Register the config class
        $this->loader->register('config', '\libs\Config\Config');
        // Register the request class and override flight's default
        $this->loader->register('request', '\libs\Request\Request');
        // Register the response class and override flight's default
        $this->loader->register('response', '\libs\Response\Response');
        // Register the router class and override flight's default
        $this->loader->register('router', '\libs\Router\Router');

        // Register framework methods into the dispatcher
        $this->dispatcher->set('start', array($this, 'start'));
        $this->dispatcher->set('stop', array($this, 'stop'));

        $initialized = true;
    }

    /**
     * Enables/disables custom error handling.
     *
     * @param bool $enabled True or false
     */
    public function handleErrors($enabled)
    {
        if ($enabled) {
            set_error_handler(array($this, 'handleError'));
            set_exception_handler(array($this, 'handleException'));
        }
        else {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    /**
     * Custom error handler. Converts errors into exceptions.
     *
     * @param int $errno Error number
     * @param int $errstr Error string
     * @param int $errfile Error file name
     * @param int $errline Error file line number
     * @throws \ErrorException
     */
    public function handleError($errno, $errstr, $errfile, $errline) {
        if ($errno & error_reporting()) {
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
    }

    /**
     * Custom exception handler. Logs exceptions.
     *
     * @param \Exception $e Thrown exception
     */
    public function handleException(\Exception $e) {
        if ($this->loader->load('config')->get('app.log_errors')) {
            error_log($e->getMessage());
        }

        $this->loader->load('response')->error($e);
    }

    /**
     * Maps a callback to a framework method.
     *
     * @param string $name Method name
     * @param callback $callback Callback function
     * @throws \Exception If trying to map over a framework method
     */
    public function map($name, $callback) {
        if (method_exists($this, $name)) {
            throw new \Exception('Cannot override an existing framework method.');
        }

        $this->dispatcher->set($name, $callback);
    }

    /**
     * Registers a class to a framework method.
     *
     * @param string $name Method name
     * @param string $class Class name
     * @param array $params Class initialization parameters
     * @param callback $callback Function to call after object instantiation
     * @throws \Exception If trying to map over a framework method
     */
    public function register($name, $class, array $params = array(), $callback = null) {
        if (method_exists($this, $name)) {
            throw new \Exception('Cannot override an existing framework method.');
        }

        $this->loader->register($name, $class, $params, $callback);
    }

    /**
     * Adds a pre-filter to a method.
     *
     * @param string $name Method name
     * @param callback $callback Callback function
     */
    public function before($name, $callback) {
        $this->dispatcher->hook($name, 'before', $callback);
    }

    /**
     * Adds a post-filter to a method.
     *
     * @param string $name Method name
     * @param callback $callback Callback function
     */
    public function after($name, $callback) {
        $this->dispatcher->hook($name, 'after', $callback);
    }

    /**
     * Adds a path for class autoloading.
     *
     * @param string $dir Directory path
     */
    public function path($dir) {
        $this->loader->addDirectory($dir);
    }

    /**
     * Starts the framework.
     */
    public function start() {
        $dispatched = false;
        $self = $this;
        $request = $this->loader->load('request');
        $response = $this->loader->load('response');
        $router = $this->loader->load('router');

        // Flush any existing output
        if (ob_get_length() > 0) {
            $response->write(ob_get_clean());
        }

        // Enable output buffering
        ob_start();

        // Enable error handling
        $this->handleErrors($this->loader->load('config')->get('app.handle_errors'));

        // Disable caching for AJAX requests
        if ($request->ajax) {
            $response->cache(false);
        }

        // Allow post-filters to run
        $this->after('start', function() use ($self) {
            $self->stop();
        });

        // Route the request
        while ($route = $router->route($request)) {

            // Start the Session
            if(!isset($_SESSION)){
                session_name($this->loader->load('config')->get('app.name'));
                session_start();
            }

        	if(is_array($route->callback)){
        		
                // Set method
                if(!isset($route->callback[1])){
		        	$route->callback[1] = !empty($route->params['action']) ? $route->params['action'] : null;
		        }
		        $route->callback[1] = $this->resolve_restful_action($route->callback[1], $route->callback[0]);

                // Register hook so that you can run a filter before and after the controller's method
                if(is_callable(array($route->callback[0], $route->callback[1]))){
                    $hookname = $route->callback[0].'.'.$route->callback[1];
                    $params = array_values($this->loader->load('arr')->except($route->params,  array('lang','action')));
                    $this->dispatcher->set($hookname, array(new $route->callback[0], $route->callback[1]));
                    $continue = $this->dispatcher->run($hookname, $params);
                }else{
                    $this->loader->load('response')->notFound();
                }
                
        	}else{
                $params = array_values($route->params);
                $continue = $this->dispatcher->execute($route->callback, $params);
            }

            $dispatched = true;

            if (!$continue) break;

            $router->next();
        }

        if (!$dispatched) {
            $this->loader->load('response')->notFound();
        }
    }

    /**
     * Stops the framework and outputs the current response.
     *
     * @param int $code HTTP status code
     */
    public function stop($code = 200) {
        $this->loader->load('response')
            ->status($code)
            ->write(ob_get_clean())
            ->send();
    }

    /**
     * Returns the ioc container.
     *
     */
    public function getLoader(){
        return $this->loader;
    }

    /**
     * Returns the dispatcher.
     *
     */
    public function getDispatcher(){
        return $this->dispatcher;
    }

    /**
     * Fires an event.
     *
     * @param string $name Event name
     * @param array $params Callback parameters
     * @return string Output of callback
     */
    public function fire($name, array $params = array()) {
        return $this->dispatcher->run($name, $params);
    }

    /**
     * Loads a registered class from the ioc container.
     *
     * @param string $class Class name
     * @param bool $shared Shared instance
     * @return object Class instance
     */
    public function make($class, $shared = true){
        return $this->loader->load($class, $shared);
    }

    /**
     * Resolves a RESTful action.
     *
     * @param string $action Action name
     * @param string $controller Controller name
     * @return string RESTful method name     
     */
	public function resolve_restful_action($action, $controller){
        $method = strtolower($this->loader->load('request')->method);
	    $action = isset($action) ? $this->loader->load('str')->studly($action) : $this->loader->load('config')->get('app.default_action');
	    if(method_exists($controller, $method.$action)){
	        return $method.$action;
	    }
	    if(method_exists($controller, 'any'.$action)){
	        return 'any'.$action;
	    }
	}

}