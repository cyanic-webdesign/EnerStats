<?php

namespace Cyanic\Model;

use DateTime;

/**
 * 
 * EnergyUser Model class
 * 
 * @author Cyanic Webdesign
 * @copyright 2014
 *
 */
class EnergyUser extends AbstractModel
{
	protected $id;
	protected $address;
	protected $city;
	protected $cookieHash;
	protected $costsEnergyHigh;
	protected $costsEnergyLow;
	protected $costsGas;
	protected $dateCreated;
	protected $dateModified;
	protected $dateLastLogin;
	protected $email;
	protected $hash;
	protected $zipcode;
	
	
	public function getId()
	{
		return $this->id;
	}
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}
	
	
	public function getAddress() 
	{
		return $this->address;
	}
	public function setAddress($address) 
	{
		$this->address = (string) $address;
		return $this;
	}

	public function getCity() 
	{
		return $this->city;
	}
	public function setCity($city) 
	{
		$this->city = (string) $city;
		return $this;
	}
	
	
	public function getCookieHash() 
	{
		return $this->cookieHash;
	}
	public function setCookieHash($hash) 
	{
		$this->cookieHash = (string) $hash;
		return $this;
	}	

	
	public function getCostsEnergyHigh() 
	{
		return $this->costsEnergyHigh;
	}
	public function setCostsEnergyHigh($costs) 
	{
		$this->costsEnergyHigh = (string) $costs;
		return $this;
	}

	
	public function getCostsEnergyLow() 
	{
		return $this->costsEnergyLow;
	}
	public function setCostsEnergyLow($costs) 
	{
		$this->costsEnergyLow = (string) $costs;
		return $this;
	}

	
	public function getCostsGas() 
	{
		return $this->costsGas;
	}
	public function setCostsGas($costs) 
	{
		$this->costsGas = (string) $costs;
		return $this;
	}

	
	public function getDateCreated()
	{
		return $this->dateCreated;	
	}
	public function setDateCreated($date)
	{
		if ($date instanceof DateTime) {
            $this->dateCreated = $date;
        } else {
            $this->dateCreated = new DateTime($date);
        }		
		return $this;
	}
	
	
	public function getDateModified()
	{
		return $this->dateModified;	
	}
	public function setDateModified($date)
	{
		if ($date instanceof DateTime) {
            $this->dateModified = $date;
        } else {
            $this->dateModified = new DateTime($date);
        }				
		return $this;
	}	
	
	
	public function getDateLastLogin()
	{
		return $this->dateLastLogin;	
	}
	public function setDateLastLogin($date)
	{
		if ($date instanceof DateTime) {
            $this->dateLastLogin = $date;
        } else {
            $this->dateLastLogin = new DateTime($date);
        }				
		return $this;
	}	

	
	public function getEmail()
	{
		return $this->email;
	}
	public function setEmail($email) 
	{
		$this->email = (string) $email;
		return $this;
	}

	
	public function getHash()
	{
		return $this->hash;
	}
	public function setHash($hash) 
	{
		$this->hash = (string) $hash;
		return $this;
	}
	
	
	public function getZipcode() 
	{
		return $this->zipcode;
	}
	public function setZipcode($zipcode) 
	{
		$this->zipcode = (string) $zipcode;
		return $this;
	}
}