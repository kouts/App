<?php

class controllers_Test extends controllers_Main {

    public function getIndex(){
    	echo 'This is index action of the test controller.';
    }


    public function getList($name = null){
    	echo 'This is the list action of the test controller.<br />';
    	echo 'Hello '.$name;
    }

}