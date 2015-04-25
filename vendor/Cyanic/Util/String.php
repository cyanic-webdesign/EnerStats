<?php

namespace Cyanic\Util;

/**
 * 
 * String class
 * 
 * @author 		Cyanic Webdesign
 * @copyright	2012
 *
 */
class String
{
	/**
	 * 
	 * Function that returns a string from camelCasing
	 * to a string with underscores
	 *
	 */
	public static function fromCamelCase($string)
	{
		//	anonymous transform function
		$transform = function($letters) 
		{
        	$letter = array_shift($letters);
            return '_' . strtolower($letter);
        };    		
        
        return preg_replace_callback('/([A-Z])/', $transform, $string);
	}
	
	
	/**
	 * 
	 *  Function that returns a string with underscores to
	 *  a string with camelCasing
	 *  
	 *  @param string
	 *  @return string
	 *  
	 */
	public static function toCamelCase($string)
	{
		return implode('', array_map('ucfirst', explode('_', $string)));
	}

	
	/**
	 * 
	 * Function that returns a string to an url
	 *
	 */
	public static function toUrl($string)
	{
		$string = str_replace('@', '-at-', $string);
		$string = str_replace('&', '-en-', $string);
		$string = str_replace('(', '', $string);
		$string = str_replace(')', '', $string);

		
		$replace = function($string) 
		{			
			$string = preg_replace('/([!,&,.,+,:,;,@,%,$,\',",?,,,#])/', '', $string);
			$string = preg_replace('~&([a-z]{1,2})(acute|caron|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));			
			
			return strtolower($string);
		};
		
		$url = implode('-', array_map($replace, explode(' ', $string)));
		$url = str_replace('---', '-', $url);
		$url = str_replace('--', '-', $url);
		
		return $url;
	}
	
	
	/**
	 *
	 * Function that wraps a text
	 *
	 */
    public static function wrap($text = '', $chars = 40, $elli = '&#8230;')
    {
        $text = ltrim(str_replace('<p></p>', '', $text));
        $wordwrap = wordwrap($text, $chars, "\n", false);
	
        if(stristr($wordwrap, "\n")) {
			list($new_string, $elli) = explode("\n", $wordwrap);
        } else {
            $new_string = $wordwrap;
    		$elli = false;
    	}

        //    if theres an alinea no show the elli ;)
        if(strstr($new_string, '</p>')){
            $wrappedText = $new_string;
        } else {
            $wrappedText = ( $elli ) ? $new_string.'...' : $new_string;
        }

        return $wrappedText;
    }	
}
