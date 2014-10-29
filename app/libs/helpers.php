<?php

// For debugging
function print_pre($array, $die = true){
	print("<pre>".print_r($array,true)."</pre>");
	if($die === true){
		die();
	}
}

// For debugging
function dump_pre($obj, $die = true){
	print("<pre>".var_dump($obj)."</pre>");
	if($die === true){
		die();
	}
}

// For debugging
function dd(){
	array_map(function($x) { var_dump($x); }, func_get_args());
	die();
}