<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage RAIDERSPIRIT
 * @since RAIDERSPIRIT 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('raiderspirit_storage_get')) {
	function raiderspirit_storage_get($var_name, $default='') {
		global $RAIDERSPIRIT_STORAGE;
		return isset($RAIDERSPIRIT_STORAGE[$var_name]) ? $RAIDERSPIRIT_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('raiderspirit_storage_set')) {
	function raiderspirit_storage_set($var_name, $value) {
		global $RAIDERSPIRIT_STORAGE;
		$RAIDERSPIRIT_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('raiderspirit_storage_empty')) {
	function raiderspirit_storage_empty($var_name, $key='', $key2='') {
		global $RAIDERSPIRIT_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($RAIDERSPIRIT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($RAIDERSPIRIT_STORAGE[$var_name][$key]);
		else
			return empty($RAIDERSPIRIT_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('raiderspirit_storage_isset')) {
	function raiderspirit_storage_isset($var_name, $key='', $key2='') {
		global $RAIDERSPIRIT_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($RAIDERSPIRIT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($RAIDERSPIRIT_STORAGE[$var_name][$key]);
		else
			return isset($RAIDERSPIRIT_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('raiderspirit_storage_inc')) {
	function raiderspirit_storage_inc($var_name, $value=1) {
		global $RAIDERSPIRIT_STORAGE;
		if (empty($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = 0;
		$RAIDERSPIRIT_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('raiderspirit_storage_concat')) {
	function raiderspirit_storage_concat($var_name, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (empty($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = '';
		$RAIDERSPIRIT_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('raiderspirit_storage_get_array')) {
	function raiderspirit_storage_get_array($var_name, $key, $key2='', $default='') {
		global $RAIDERSPIRIT_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($RAIDERSPIRIT_STORAGE[$var_name][$key]) ? $RAIDERSPIRIT_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($RAIDERSPIRIT_STORAGE[$var_name][$key][$key2]) ? $RAIDERSPIRIT_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('raiderspirit_storage_set_array')) {
	function raiderspirit_storage_set_array($var_name, $key, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if ($key==='')
			$RAIDERSPIRIT_STORAGE[$var_name][] = $value;
		else
			$RAIDERSPIRIT_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('raiderspirit_storage_set_array2')) {
	function raiderspirit_storage_set_array2($var_name, $key, $key2, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name][$key])) $RAIDERSPIRIT_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$RAIDERSPIRIT_STORAGE[$var_name][$key][] = $value;
		else
			$RAIDERSPIRIT_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('raiderspirit_storage_merge_array')) {
	function raiderspirit_storage_merge_array($var_name, $key, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if ($key==='')
			$RAIDERSPIRIT_STORAGE[$var_name] = array_merge($RAIDERSPIRIT_STORAGE[$var_name], $value);
		else
			$RAIDERSPIRIT_STORAGE[$var_name][$key] = array_merge($RAIDERSPIRIT_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('raiderspirit_storage_set_array_after')) {
	function raiderspirit_storage_set_array_after($var_name, $after, $key, $value='') {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if (is_array($key))
			raiderspirit_array_insert_after($RAIDERSPIRIT_STORAGE[$var_name], $after, $key);
		else
			raiderspirit_array_insert_after($RAIDERSPIRIT_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('raiderspirit_storage_set_array_before')) {
	function raiderspirit_storage_set_array_before($var_name, $before, $key, $value='') {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if (is_array($key))
			raiderspirit_array_insert_before($RAIDERSPIRIT_STORAGE[$var_name], $before, $key);
		else
			raiderspirit_array_insert_before($RAIDERSPIRIT_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('raiderspirit_storage_push_array')) {
	function raiderspirit_storage_push_array($var_name, $key, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($RAIDERSPIRIT_STORAGE[$var_name], $value);
		else {
			if (!isset($RAIDERSPIRIT_STORAGE[$var_name][$key])) $RAIDERSPIRIT_STORAGE[$var_name][$key] = array();
			array_push($RAIDERSPIRIT_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('raiderspirit_storage_pop_array')) {
	function raiderspirit_storage_pop_array($var_name, $key='', $defa='') {
		global $RAIDERSPIRIT_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($RAIDERSPIRIT_STORAGE[$var_name]) && is_array($RAIDERSPIRIT_STORAGE[$var_name]) && count($RAIDERSPIRIT_STORAGE[$var_name]) > 0) 
				$rez = array_pop($RAIDERSPIRIT_STORAGE[$var_name]);
		} else {
			if (isset($RAIDERSPIRIT_STORAGE[$var_name][$key]) && is_array($RAIDERSPIRIT_STORAGE[$var_name][$key]) && count($RAIDERSPIRIT_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($RAIDERSPIRIT_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('raiderspirit_storage_inc_array')) {
	function raiderspirit_storage_inc_array($var_name, $key, $value=1) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if (empty($RAIDERSPIRIT_STORAGE[$var_name][$key])) $RAIDERSPIRIT_STORAGE[$var_name][$key] = 0;
		$RAIDERSPIRIT_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('raiderspirit_storage_concat_array')) {
	function raiderspirit_storage_concat_array($var_name, $key, $value) {
		global $RAIDERSPIRIT_STORAGE;
		if (!isset($RAIDERSPIRIT_STORAGE[$var_name])) $RAIDERSPIRIT_STORAGE[$var_name] = array();
		if (empty($RAIDERSPIRIT_STORAGE[$var_name][$key])) $RAIDERSPIRIT_STORAGE[$var_name][$key] = '';
		$RAIDERSPIRIT_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('raiderspirit_storage_call_obj_method')) {
	function raiderspirit_storage_call_obj_method($var_name, $method, $param=null) {
		global $RAIDERSPIRIT_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($RAIDERSPIRIT_STORAGE[$var_name]) ? $RAIDERSPIRIT_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($RAIDERSPIRIT_STORAGE[$var_name]) ? $RAIDERSPIRIT_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('raiderspirit_storage_get_obj_property')) {
	function raiderspirit_storage_get_obj_property($var_name, $prop, $default='') {
		global $RAIDERSPIRIT_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($RAIDERSPIRIT_STORAGE[$var_name]->$prop) ? $RAIDERSPIRIT_STORAGE[$var_name]->$prop : $default;
	}
}
?>