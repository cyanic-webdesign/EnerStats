<?php

/**
 * 
 * Autload function that lazy loads the files when called upon
 * 
 * @param	string	$classname
 * 
 */
function __autoload($classname) 
{
	$classname = ltrim($classname, "\\");
	preg_match('/^(.+)?([^\\\\]+)$/U', $classname, $match);

	$classname = $_SERVER['DOCUMENT_ROOT'].'/vendor/' 
			   . str_replace("\\", "/", $match[1]) 
	 		   . str_replace(array("\\", "_"), "/", $match[2]) 
			   . ".php";
		   
    require $classname;
}	
