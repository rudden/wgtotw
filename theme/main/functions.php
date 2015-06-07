<?php

/**
 * Theme-related functions
 */




/**
 * Crop string and return a selected number of words
 * 
 * @param  string $string which string to manipulare
 * @param  int $no how many words to reutn
 * 
 * @return string modified string
 */
function selectNoOfWords($string, $no)
{
	return implode(' ', array_slice(explode(' ', $string), 0, $no));
}