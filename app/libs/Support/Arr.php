<?php namespace libs\Support;

class Arr {

	/**
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public static function except($array, $keys)
	{
		return array_diff_key($array, array_flip((array) $keys));
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $path
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get($array, $path, $default = null){
		$current = $array;
		$p = strtok($path, '.');
		while($p !== false){
			if (!isset($current[$p])){
				return $default;
			}
			$current = $current[$p];
			$p = strtok('.');
		}
		return $current;
	}

	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	public static function set(&$array, $key, $value){
		if (is_null($key)) return $array = $value;
		$keys = explode('.', $key);
		while (count($keys) > 1){
			$key = array_shift($keys);
			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset($array[$key]) || ! is_array($array[$key])){
				$array[$key] = array();
			}
			$array =& $array[$key];
		}
		$array[array_shift($keys)] = $value;
		return $array;
	}

}