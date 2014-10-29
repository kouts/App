<?php namespace libs\Config;

use \libs\Support\Arr;

class Config {

	protected $items = array();

	public function __construct(array $items = array()){
		$this->items = array();
	}

	public function set($key, $val){
		if(is_array($val)){
			$this->items[$key] = $val;
		}else{
			Arr::set($this->items, $key, $val);
		}
	}

	public function get($key, $default = null){
		return Arr::get($this->items, $key, $default);
	}

	public function all(){
		return $this->items;
	}

}