<?php namespace libs\Translation;

class Translation {

	private $directory;
	private $lang;
	private $files = array();
	private $translations = array();

	public function get($filedotkey){
		$filedotkey = explode('.', $filedotkey, 2);
		$key = array_pop($filedotkey);
		if(!empty($filedotkey)){
			$this->addFile($filedotkey[0]);
		}
		return isset($this->translations[$key]) ? $this->translations[$key] : '<b>!!</b>_'.$key.'_<b>!!</b>'; 
	}

	public function addFile($file){
		if(!isset($this->files[$file])){
			$this->files[$file] = true;
			$this->translations = array_merge($this->translations, @include_once($this->directory.'/'.$this->lang.'/'.$file.'.php'));
		}
		return $this;
	}

	public function setDirectory($directory){
		$this->directory = $directory;
		return $this;
	}

	public function setLang($lang){
		$this->lang = $lang;
		return $this;
	}

}