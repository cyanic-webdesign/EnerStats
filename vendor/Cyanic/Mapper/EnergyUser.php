<?php

namespace Cyanic\Mapper;

use Cyanic\DbAdapter;
use Cyanic\Model\EnergyUser as EnergyUserModel;

/**
 * 
 * EnergyUser Mapper class
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2014
 *
 */
class EnergyUser extends AbstractMapper 
{
	protected $adapter;
	protected $classname = 'Cyanic\Model\EnergyUser';
	protected $tablename = 'p1_user';
	protected $primaryKey = 'id';

	
	/**
	 * 
	 * Constructor with factory patter for the database adapter
	 * 
	 * @param $adapter
	 * 
	 */
	public function __construct(DbAdapter $adapter) 
	{
		$this->adapter = $adapter;		
	}	

	
	/**
	 * 
	 * Implement abstract functions
	 * 
	 */
	public function getClassname()
	{
		return $this->classname;
	}	
	public function getTablename()
	{
		return $this->tablename;
	}		
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
	
	
	/**
	 * 
	 * Function that validates the users hash
	 * 
	 * @param EnergyUser $user
	 * @param string $password
	 * @return bool	$result
	 * 
	 * 
	 */
	public function validate(EnergyUserModel $user, $password)
	{
		return (crypt($password, $user->getHash()) == $user->getHash());
	}
	
	
	/**
	 * 
	 * Function that creates a hash
	 * 
	 * @param	string	$password
	 * @return	string	$hash
	 *  
	 */
	public function createHash($password)
	{
		//	create unique salt
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		
		return crypt($password, '#n3rSt4tz!$2014#@c!W' . $salt);
	}
}
