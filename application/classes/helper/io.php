<?php
/**
@author Ivan Checkhov Lodoss Team 2011
**/
class Helper_IO
{
	private static $output 	= array();
	private static $id;
	
	public static function grab($id)
	{
		ob_start();
		self::$id = $id;
	}
	
	public static function stop()
	{
		self::$output[self::$id][] = ob_get_contents();
		ob_end_clean();
	}
	
	public static function get($id)
	{
		$contents = isset(self::$output[$id]) ? self::$output[$id] : array();
		return implode('', $contents);
	}
	
}