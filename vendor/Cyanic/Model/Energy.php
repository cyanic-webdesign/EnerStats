<?php

namespace Cyanic\Model;

use DateTime;

/**
 * 
 * Energy Model class
 * 
 * @author Cyanic Webdesign
 * @copyright 2014
 *
 */
class Energy extends AbstractModel
{
	protected $id;	
	protected $currentUsage;
	protected $currentRestitution;
	protected $dateCreated;
	protected $gasUsage;
	protected $rate;
	protected $t1Usage;
	protected $t2Usage;
	protected $t1Restitution;
	protected $t2Restitution;
	
	
	public function getId()
	{
		return $this->id;
	}
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}
	
	
	public function getCurrentUsage() 
	{
		return $this->currentUsage;
	}
	public function setCurrentUsage($usage) 
	{
		$this->currentUsage = $usage;
		return $this;
	}

	
	public function getCurrentRestitution() 
	{
		return $this->currentRestitution;
	}
	public function setCurrentRestitution($restitution) 
	{
		$this->currentRestitution = $restitution;
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
			$date = (strlen($date) == 4) ? $date . '-01-01' : $date;
            $this->dateCreated = new DateTime($date);
        }		
		return $this;
	}

	
	public function getGasUsage() 
	{
		return $this->gasUsage;
	}
	public function setGasUsage($usage) 
	{
		$this->gasUsage = $usage;
		return $this;
	}

	
	public function getRate() 
	{
		return $this->rate;
	}
	public function setRate($rate) 
	{
		$this->rate = (int) $rate;
		return $this;
	}

	
	public function getT1Usage() 
	{
		return $this->t1Usage;
	}
	public function setT1Usage($usage) 
	{
		$this->t1Usage = $usage;
		return $this;
	}

	
	public function getT2Usage() 
	{
		return $this->t2Usage;
	}
	public function setT2Usage($usage) 
	{
		$this->t2Usage = $usage;
		return $this;
	}

	
	public function getT1Restitution() 
	{
		return $this->t1Restitution;
	}
	public function setT1Restitution($restitution) 
	{
		$this->t1Restitution = $restitution;
		return $this;
	}

	
	public function getT2Restitution() 
	{
		return $this->t2Restitution;
	}
	public function setT2Restitution($restitution) 
	{
		$this->t2Restitution = $restitution;
		return $this;
	}
}