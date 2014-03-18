<?php

namespace Cyanic\Mapper;

use DateTime;
use InvalidArgumentException;
use PDO;


/**
 * 
 * Abstract Mapper class for OOP use
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2012
 *
 */
abstract class AbstractMapper
{
	/**
	 * 
	 * Abstract functions that needs to be inherited
	 * 
	 */
	abstract public function getClassname();
	abstract public function getTablename();
	abstract public function getPrimaryKey();

	
	/**
	 * 
	 * Function that gets the injected database adapter
	 * 
	 */
	public function getAdapter()
	{
		return $this->adapter;
	}
	
	
	/**
	 * 
	 * Function to find one row by it's primary key
	 * 
	 * @param	int	$id
	 * 
	 */
	public function find($id = 0)
	{		
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare('SELECT * 
										  			       FROM '.$this->getTablename().' 
										  			  	   WHERE '.$this->getPrimaryKey().' = :id');
		//	bind the id param
		$query->execute(array('id' => intval($id)));		
		
		//	fetch the row and return an object
		return $this->toObject($query->fetch(PDO::FETCH_ASSOC));
	}
	
	
	/**
	 * 
	 * Fetch a row from the database defined by the params
	 * 
	 * @params	string	$statement
	 * @param	array	$params
	 * @return	mixed	$object
	 * 
	 */
	public function fetchRow($statement = '', $params = null)
	{	
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare('SELECT * 
											 			   FROM '.$this->getTablename().'
											 			   WHERE '.$statement);
		//	bind the params
		$query->execute($params);
		
		//	fetch the row and return an object
		return $this->toObject($query->fetch(PDO::FETCH_ASSOC));				
	}
	
	
	/**
	 * 
	 * Fetch filtered rows from the database
	 * 
	 * @param	string	$statement
	 * @param	array	$params
	 * @return	mixed	$objects 
	 * 
	 */
	public function fetchFiltered($statement = '', $params = null)
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare('SELECT * 
											 			   FROM '.$this->getTablename().'
											 			   WHERE '.$statement);
		//	bind the params
		$query->execute($params);		
		
		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));	
	}
	
	
	/**
	 * 
	 * Fetch all rows from the database
	 * 
	 * @return	array	$objects
	 * 
	 */
	public function fetchAll()
	{
		//	create the query
		$result = $this->adapter->getConnection()->query('SELECT * FROM '.$this->getTablename());
		
		return $this->toObjectArray($result->fetchAll(PDO::FETCH_ASSOC));		
	}
	
	
	/**
	 * 
	 * Function that saves a user object
	 * 
	 * @param	User	$object
	 * @return	User	$object
	 * 
	 */
	public function save($object = null)
	{
		if(get_class($object) != $this->getClassname()) {
			throw new InvalidArgumentException('Object ('.get_class($object).') does not match the class name ('.$this->getClassname().')');
		}
		
		//	create the params
		$params = $this->toScalarArray($object);
		
		//	initiate datetime
		$insert = $values = $update = '';
		
		//	update
		if($object->getId() > 0) {

			//	create update string			
			foreach($params as $key => $value) {
				$update .= $key . ' = :' .$key . ', ';				
			}
			
			//	prepare the update query and execute
			$query = $this->adapter->getConnection()->prepare('UPDATE '.$this->getTablename().' SET '.trim($update, ', ').' WHERE id = '.$object->getId());
			$query->execute($this->toBindedParams($params));
			
		//	insert
		} else {
			
			//	create insert strings
			foreach($params as $key => $value) {
				$insert .= $key.', ';
				$values .= ':' . $key . ', '; 
			}			
			
			//	prepare the insert query and execute
			$query = $this->adapter->getConnection()->prepare('INSERT INTO '. $this->getTablename() .' ('. trim($insert,', ') .') VALUES('. trim($values, ', ') .')');					 
			$query->execute($this->toBindedParams($params));

			//	update object
			$object->setId($this->getLastInsertedId());
		}
	}
	
	
	/**
	 * 
	 * Function that deletes an object from the database
	 * 
	 * @param	object	$object
	 * @return	mixed	$result
	 * 
	 */
	public function delete($object = null)
	{
		//	prepare the delete statement
		$query = $this->adapter->getConnection()->prepare('DELETE FROM '.$this->getTablename().'
											 			   WHERE id = :id');
		//	bind the id 
		$query->bindParam(':id', $object->getId());
		
		//	return the execution
		return $query->execute();
	}
	
	
	/**
	 * 
	 * Function that returns the last inserted id
	 * 
	 * @return	int	$id
	 * 
	 */
	public function getLastInsertedId()
	{
		return $this->adapter->getConnection()->lastInsertId();
	}
	
	
	/**
	 * 
	 * Function that creates an object of a row
	 * 
	 * @param	array	$row
	 * @return	mixed	$object
	 * 
	 */
	protected function toObject($row)
	{		
		//	get the class name
		$class = (string) $this->getClassname();
		
		//	create an object
		$object = ($row) ? new $class() : false;
		
		//	walkthrough the row
		if($object) {
			foreach($row as $key => $value) {
				//	create the method
				$method = $this->toMethod($key);
				//	set the value			
				$object->$method($value);			
			}
		}
		
		//	return the created object
		return $object;
	}
	
	
	/**
	 * 
	 * Function that creates an array of objects from the results
	 * 
	 * @param	array	$result
	 * @return	array	$objects
	 * 
	 */
	protected function toObjectArray($result = null)
	{
		//	initiate
		$objects = array();
		
		//	walktrough the result and create objects
		if($result) {
			foreach($result as $row) {
				$objects[] = $this->toObject($row);
			}
		}
		
		//	return the array
		return $objects;
	}
	
	
	/**
	 * 
	 * Function that creates setter or getter methods
	 * 
	 * @param	string	$value
	 * @param	string	$type
	 * @return	string	$method
	 * 
	 */
	protected function toMethod($value, $type = 'set') 
	{
		return $type . implode('', array_map('ucfirst', explode('_',$value)));
	}
	
	
	/**
	 * 
	 * Function that creates a propertie of a value
	 * 
	 * @param	string	$value
	 */
	protected function toProperty($value)
	{
		return trim(preg_replace_callback('/([A-Z])/', function($c){ return '_'.strtolower($c[1]); }, $value), '_');		
	}
	
	
	/**
	 * 
	 * Function that returns a binded array through lambda function 
	 * 
	 * @param 	array	$params
	 * @return	array	$bindedParams
	 * 
	 */
	protected function toBindedParams($params = array())
	{
		return array_combine(array_map(function($key) { return ':'.$key; }, array_keys($params)), array_values($params));
	}
	
	
	/**
	 * 
	 * Function that creates a scalar array from an object
	 * 
	 * @param	object	$object
	 * @return	array	$params
	 * 
	 */
	protected function toScalarArray($object = null)
	{
		$params = array();
		
		if(is_object($object)) {
			foreach($object->getProperties() as $key => $value) {				
				if($key != $this->getPrimaryKey()) {				
					if(is_scalar($value)) {
						$params[$this->toProperty($key)] = $value;
					} elseif(is_object($value) || is_array($value)) {
						$params[$this->toProperty($key)] = $this->toScalar($value);
					} else {				
						$params[$this->toProperty($key)] = null;
					}
				}
			}
		}
	
		return $params;
	}
	
	
	/**
	 * 
	 * Function that creates a scalar variable from an object
	 * 
	 * @param	object	$object
	 * @return	string	
	 * 
	 */
	protected function toScalar($object = null)
	{		
		//	is a to string function callable on the object
		if(is_callable(array($object, '__toString'))) {
            return $object->__toString();
        }

        //	is object a DateTime instance
        if($object instanceof DateTime) {
            return $object->format('Y-m-d H:i:s');
        }		
        
        //	create a serialized string
        if(is_array($object)) {
        	return serialize($object);
        }
        
        //	throw an error
        throw new InvalidArgumentException('Can not convert object of ' . get_class($object) . ' to scalar');
	}
}
