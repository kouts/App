<?php

class controllers_Main {

	protected $vars;
	protected $route;

    public function __construct(){
    	$this->route = Route::getInstance()->current();
	    $this->vars = array_filter(explode('/', Str::cutat($this->route->splat, '?')));
	    $this->langSetup($this->route->params['lang']);
    }

    public function langSetup($lang = null){
		if(Lang::exists($lang)){
			Lang::set($lang);
		}else{
	        if(Config::get('app.lang_redirect') === true){
	        	Lang::set(Config::get('app.lang_default'));
	        	Redirect::to('/');
	        }else{
	            Response::notFound();
	        }
	        return;
		} 		

        Config::set('lang_code', Lang::code());
        Config::set('lang_iso', Lang::iso());
        Config::set('lang_text', Lang::text());

	    Translation::setDirectory(Config::get('app.translations_dir'))->setLang(Lang::code());
    }

}