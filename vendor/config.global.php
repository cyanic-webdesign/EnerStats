<?php

/**
 * 
 * Global config file 
 * 
 */

//	create a generic empty class
$config = new stdClass;

//	initiate the database
$config->dbhost = 'localhost';
$config->dbname = 'logs';
$config->dbusername = 'root';
$config->dbpassword = '';

//	return the config object
return $config;
