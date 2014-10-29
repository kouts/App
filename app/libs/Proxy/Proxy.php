<?php namespace libs\Proxy;

class Proxy {

	public $ip;
	public $port;
	public $username;
	public $password;

	public function __construct($ip, $port, $username = null, $password = null) {

		$this->ip = $ip;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;

		$http = array(
			'proxy' => 'tcp://'.$this->ip.':'.$this->port.'',
	        'request_fulluri' => true
	    );

		if($username){
			$http['header'] = base64_encode(''.$this->username.':'.$this->password.'');
		}

		// Define the default, system-wide context.
		$r_default_context = stream_context_get_default(array('http' => $http));

		// Though we said system wide, some extensions need a little coaxing.
		libxml_set_streams_context($r_default_context);

	}

	public function test($url){
		$headers = get_headers($url);
		if($headers && $headers[0]=='HTTP/1.0 200 OK'){
			return true;
		}else{
			return false;
		}
	}

}