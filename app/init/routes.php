 <?php

// Test pure closure route
Route::map('/closure', function(){
	echo 'This is closure route';
});

// Test controller route
Route::map('/test(/@action(/@name))/*', array('controllers_Test'));

// Test closure route
Route::map('/', function(){
	echo 'This is the home page';
});