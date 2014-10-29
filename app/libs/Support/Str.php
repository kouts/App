<?php namespace libs\Support;

class Str {

	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	public static function contains($haystack, $needles)
	{
		foreach ((array) $needles as $needle)
		{
			if ($needle != '' && strpos($haystack, $needle) !== false) return true;
		}

		return false;
	}

	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function studly($value)
	{
		$value = ucwords(str_replace(array('-', '_'), ' ', $value));

		return str_replace(' ', '', $value);
	}

	/**
	 * Return the substring before the first occurence of the $cut
	 *
	 * @param  string  $str
	 * @param  string  $cut
	 * @return string
	 */
	public static function cutat($str, $cut)
	{
	    if (strstr($str, $cut)){
	    	$str = mb_substr($str, 0, mb_strpos($str, $cut));
	    } 
	    return $str;
	}


}
