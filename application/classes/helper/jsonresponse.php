<?php 
class Helper_JsonResponse
{
	protected static $text 	= '';
	protected static $errors	= array();
	protected static $data		= array();

	public static function addError($error) 
	{
		self::$errors[] = $error;

	}

	public static function addText($text) 
	{
		self::$text = $text;

	}

	public static function addData($data) 
	{
		self::$data = $data;

	}

	public static function render()
	{
		header('Content-type: application/json');
		echo json_encode(array(
				'text' 		=> self::$text,
				'errors'	=> self::$errors,
				'data'		=> self::$data
			));
		die();
	}
}