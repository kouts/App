<?php namespace libs\Request;

use \libs\Support\Arr;
use \libs\Support\Str;

class Request extends \flight\net\Request {

	public function ajax(){
		return $this->ajax;
	}

	public function uri(){
		return '/'.trim(Str::cutat($this->url, '?'), '/');
	}

	public function get($var, $default = null){
		return Arr::get($this->all(), $var, $default);
	}

	public function all(){
		return array_merge($this->query->getData(), $this->data->getData());
	}

}