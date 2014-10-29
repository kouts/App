<?php namespace libs\Language;

class Language {

	public $available_languages;
	public $current;

	public function __construct(array $available_languages){
		$this->available_languages = $available_languages;
	}
	
	public function set($lang){
		$this->current = $lang;
	}

	public function exists($lang = null){
		if(isset($this->available_languages[$lang])){
			return true;
		}
		return false;
	}

	public function code(){
		return $this->current;
	}

	public function iso(){
		return $this->available_languages[$this->current]['iso'];
	}

	public function text(){
		return $this->available_languages[$this->current]['text'];
	}

	public function link($link){
		return '/'.$this->current.'/'.ltrim($link, '/');
	}

}