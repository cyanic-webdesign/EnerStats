<?php

namespace Cyanic;

use PDO;
use InvalidArgumentException;

/**
 * 
 * PDO database adapter class
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2012
 *
 */
class DbAdapter
{
	protected $connection;
	protected $dbname;
	protected $host;
	protected $username;
	protected $password;
	
	
	/**
	 * 
	 * Constructor
	 * 
	 * @param objecty $config
	 * 
	 */
	public function __construct($config = null)
	{
		//	check on the params
		if(!is_object($config)) {
			throw new InvalidArgumentException('Please define your database params correctly (dbname/host/username/password)');
		}

		//	set the properties
		$this->dbname = $config->dbname;
		$this->host = $config->dbhost;
		$this->username = $config->dbusername;
		$this->password = $config->dbpassword;
		
		//	try to connect
		$this->connect();
	}
	
	
	/**
	 * 
	 * Get the connection
	 * 
	 */
	public function getConnection()
	{
		return $this->connection;
	}	
	
	
	/**
	 * 
	 * Function that tries to connect to the database
	 * 
	 */
	protected function connect()
	{
		try {
			$this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname", 
										$this->username, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
														
		} catch (PDOException $e) {
			echo $e->getMessage();
		} 
	}
	

	/**
	 * 
	 * Function that disconnects the connection
	 * 
	 */
	public function disconnect()
	{
		$this->connection = null;
	}
}
