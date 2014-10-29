<?php namespace libs\Message;

use \libs\Response\Response;

class Message {
	
	private $msg_types = array('info', 'notice', 'error', 'success');
	private $json_data = array();
	private $response;
	
	public function __construct(Response $response) {
		$this->response = $response;
		if(!isset($_SESSION['flash_messages'])){
			$_SESSION['flash_messages'] = array();
		}
	}
	
	public function add($type, $message){
		
		if(!in_array($type, $this->msg_types)){
			die(strip_tags($type).' is not a valid message type!');
		}
		$_SESSION['flash_messages'][$type][] = htmlspecialchars_decode($message);
		return $this;
	}
	
	public function get($type='all'){

		$msgs_to_return = array();

		if($type=='all'){
			foreach($_SESSION['flash_messages'] as $msg_type => $msgs){
				foreach($msgs as $msg){
					$msgs_to_return[] = array('text'=>$msg, 'type'=>$msg_type);
				}
			}
		}else{
			if(isset($_SESSION['flash_messages'][$type])){
				foreach($_SESSION['flash_messages'][$type] as $msg){
					$msgs_to_return[] = array('text'=>$msg, 'type'=>$type);
				}
			}
		}

		return $msgs_to_return;

	}

	public function clear($type='all'){
		if($type == 'all'){
			unset($_SESSION['flash_messages']); 
		}else{
			unset($_SESSION['flash_messages'][$type]);
		}
		return true;
	}

    /**
    * Put a javascript function to the response
    * Ajax response
    *
    */
    public function addJsFunction($js_function_name, $js_params=array()){
        $this->json_data['js_function'] = array('func_name' => $js_function_name, 'args' => $js_params);
        return $this;
    }

    /**
    * Returns the $json_data (contains messages and javascript function) as json
    * Ajax response
    *
    */
    public function jfire($type='all'){
        $this->json_data['msgs'] = $this->get($type);
        $this->clear($type);
	    $this->response->json($this->json_data);
    }

}