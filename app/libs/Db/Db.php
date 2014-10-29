<?php namespace libs\Db;

use \PDO;

class Db{
	
	private $pdo;

	public function __construct($host, $dbname, $user, $pass){
        
        // Set options
		$options = array(
			PDO::ATTR_ERRMODE       		=> PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8"
		);
        
        // Create a new PDO instance
		try{
			$this->pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass, $options);
		}
        
        // Catch any errors
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function pdo(){
		return $this->pdo;
	}

	public function q($sql, $pairs=array()){ //Function to return values (select, show table)
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_NUM);
		$sth->execute($pairs);
		return $sth->fetchAll();
	}

	public function qkp($sql, $pairs=array()){ //Function to return key pair values
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_KEY_PAIR);
		$sth->execute($pairs);
		return $sth->fetchAll();
	}

	public function q_a($sql, $pairs=array()){ //Function to return values (select, show table)
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute($pairs);
		return $sth->fetchAll();
	}

	public function q_o($sql, $pairs=array(), $object){ //Function to put assoc values into object
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_INTO, $object);
		$sth->execute($pairs);
		return $sth->fetch();
	}

	public function qq($sql, $pairs=array()){ //Function just to execute queries (insert, update, delete)
		$sth = $this->pdo->prepare($sql);
		return $sth->execute($pairs); //returns true or false
	}

	public function get_values($sql, $pairs=array()){
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_NUM);
		$sth->execute($pairs);
		return $sth->fetch();
	}

	public function get_values_a($sql, $pairs=array()){
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute($pairs);
		return $sth->fetch();
	}

	public function get_value($sql, $pairs=array()){
		$sth = $this->pdo->prepare($sql);
		$sth->setFetchMode(PDO::FETCH_NUM);
		$sth->execute($pairs);
		return $sth->fetchColumn(0);
	}

	public function get_columns($sql, $pairs=array()){
		$sth = $this->pdo->prepare($sql);
		$sth->execute($pairs);
		return $sth->fetchAll(PDO::FETCH_COLUMN);
	}

	public function get_last_id(){
		return $this->pdo->lastInsertId();
	}

	public function test(){
		echo 'db test';
	}

}