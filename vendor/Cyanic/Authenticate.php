<?php 

namespace Cyanic;

use Cyanic\Model\EnergyUser as EnergyUserEntity;
use Cyanic\Mapper\EnergyUser as EnergyUserMapper;


/**
 * 
 * (Simple) Authenticate class
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2014
 *
 */
class Authenticate 
{
	const COOKIE_NAME = 'enerstats_sso';
	const SESSION_NAME = 'enerstats_user';
	
	protected $energyUser;
	protected $energyUserMapper;
	protected $hash = '#n3rSt4tz!$2014#@c!W';
	protected $salt;
	

	
	/**
	 * 
	 * Contstructor needs the energy mapper
	 * 
	 * @param EnergyUserMapper $mapper
	 * 
	 */
	public function __construct(EnergyUserMapper $mapper)
	{
		$this->setEnergyUserMapper($mapper);
	}
	
	
	/**
	 * 
	 * Authentica an user and set a cookie
	 * 
	 * @param EnergyUser $user
	 * @param bool $cookie
	 * 
	 */
	public function authenticate(EnergyUserEntity $energyUser, $cookie = false)
	{
		if($energyUser) {
			$_SESSION[self::SESSION_NAME] = crypt(session_id(), $this->getHash() . $this->getSalt());
			if($cookie) {
				$hash = crypt($energyUser->getId(), $this->getHash() . $this->getSalt());
				setcookie(self::COOKIE_NAME, $hash, time()+31536000, '/');
				$energyUser->setCookieHash($hash);
				$this->getEnergyUserMapper()->save($energyUser);
			}
			$energyUser->getDateLastLogin('now');
		}
	}
	
	
	/**
	 * 
	 * Check if an user is authenticated
	 * 
	 */
	public function isAuthenticated()
	{
		//	initiate result
		$result = false;
		//	session check
		if(isset($_SESSION[self::SESSION_NAME])) {
			$result = (crypt(session_id(), $_SESSION[self::SESSION_NAME]) == $_SESSION[self::SESSION_NAME]);
		}
		//	cookie check
		if($result === false) {
			if(isset($_COOKIE[self::COOKIE_NAME])) {				
				$energyUser = $this->getEnergyUser();
				if($energyUser) {
					$result = crypt($energyUser->getId(), $_COOKIE[self::COOKIE_NAME]) == $_COOKIE[self::COOKIE_NAME];
					if($result) {
						$this->authenticate($energyUser, false);
					}
				}
			}
		}
				
		return $result;
	}
	
	
	/**
	 * 
	 * Deactivate an use by deleting and resetting the session and cookie
	 * 
	 */
	public function deactivate()
	{
		//	delete session
		unset($_SESSION[self::SESSION_NAME]);
		
		//	delete cookie
		if(isset($_COOKIE[self::COOKIE_NAME])) {
			setcookie (self::COOKIE_NAME, '', 1);
			setcookie (self::COOKIE_NAME, false);
			unset($_COOKIE[self::COOKIE_NAME]);
		}
		
		//	create new session
		session_regenerate_id(true);
	}

	
	/**
	 * 
	 * Getters and setters
	 * 
	 */
	public function getEnergyUser()
	{
		return $this->getEnergyUserMapper()->fetchRow('cookie_hash = :cookie', array('cookie' =>  $_COOKIE[self::COOKIE_NAME]));
	}
	private function getHash()
	{
		return $this->hash;
	}	
	private function getSalt()
	{
		return substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
	}
	private function getEnergyUserMapper()
	{
		return $this->energyUserMapper;
	}
	private function setEnergyUserMapper(EnergyUserMapper $mapper)
	{
		$this->energyUserMapper = $mapper;
	}
}