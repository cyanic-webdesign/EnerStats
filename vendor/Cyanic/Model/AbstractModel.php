<?php

namespace Cyanic\Model;

use Cyanic\Util\String;

use InvalidArgumentException;

/**
 * 
 * Abstract Model class for OOP use
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2012
 *
 */
abstract class AbstractModel
{
	/**
	 * 
	 * Overloading the set
	 * 
	 * @param string $name
	 * @param string $value
	 */
    public function __set($name = '', $value = '')
    {
        $setter = 'set' . $name;
        if (('mapper' == $name) || !is_callable(array($this, $setter))) {
            throw new InvalidArgumentException('Invalid ' . get_class($this) . ' property' . $name);
        }
        $this->$setter($value);
    }

    /**
     * 
     * Overload the get
     * 
     * @param string $name
     * 
     */
    public function __get($name = '')
    {
        $getter = 'get' . $name;
        if (('mapper' == $name) || !is_callable(array($this, $getter))) {
            throw new InvalidArgumentException('Invalid ' . get_class($this) . ' property ' . $name);
        }
        return $this->$getter();
    }	
	
	
	/**
	 * 
	 * Function that returns the properties of the class
	 * 
	 * @return	array	$objectVars
	 * 
	 */
	final public function getProperties()
	{
		return get_object_vars($this);
	}

	
	/**
	 * 
	 * Function that returns the id as string
	 * 
	 */
	final public function __toString()
	{
		return (int) $this->id;
	}	
	

	/**
	 * 
	 * Return an array from this object
	 * 
	 * @return	array
	 *
	 */
    public function getArrayCopy()     
    {        
    	return get_object_vars($this);   
    }

    
    /**
     * 
     * Return exchange an array for an object
     * 
     * @param	array
     * 
     * @return	object	$this		
     *
     */
     public function exchangeArray($array)
     {
		foreach ($array as $key => $value) {
            $setter = 'set'.String::toCamelCase($key);
            if (is_callable(array($this, $setter))) {
                $this->$setter($value);
            }
        }
        
        return $this;
     }	
	
}
