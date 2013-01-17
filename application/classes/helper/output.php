<?php
class Helper_Output
{
	protected static $_css 		= array();
	protected static $_js 		= array();
	protected static $_csspath	= 'css/';
	protected static $_jspath	= 'js/';
	protected static $_errors	= array();

	public static function factory() 
	{
		return new Helper_Output();
	}

	public function link_css($css)
	{
		self::$_css[] = $css;
		return $this;
	}

	public function link_js($js)
	{
		self::$_js[] = $js;
		return $this;
	}

	public static function renderCss()
	{
		if(!empty(self::$_css)) {
			foreach (self::$_css as $key => $value) {

				$http = substr($value, 0, 4);
                                
				if($http == 'http') {
					echo '<link rel="stylesheet" type="text/css" href="'. $value .'" />';
				} else {
					echo '<link rel="stylesheet" type="text/css" href="'. URL::base( ) . self::$_csspath . $value .'.css" />';
				}

				
			}
		}
	}

	public static function renderJS()
	{
		if(!empty(self::$_js)) {
			foreach (self::$_js as $key => $value) {
				$http = substr($value, 0, 4);
				if($http == 'http') {
					echo '<script type="text/javascript" src="'. $value .'" ></script>';
				} else {
					echo '<script type="text/javascript" src="'. URL::base( ) . self::$_jspath . $value .'.js" ></script>';
				}
			}
		}
	}

	/*
	* @param Array $errors
	*/
	public static function addErrors($errors)
	{
		if(!is_array($errors) && $errors) {
			$errors = array($errors);
		}

		if($errors) {
			self::$_errors = array_merge(self::$_errors, $errors);
		}
	}

	public static function keepErrors()
	{
		$session = Session::instance();
		$session->set('errors', self::$_errors);
		
	}

	public static function getErrors()
	{
		// $session = Session::instance();
		// self::addErrors($session->get_once('errors'));
		return self::$_errors;
	}

	public static function renderErrors()
	{
		$session = Session::instance();
		self::addErrors($session->get_once('errors'));

		if(!empty(self::$_errors)) {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><strong>Oh snap!</strong> ';
			foreach (self::$_errors as $key => $value) {
				if(is_array($value)) {
					foreach ($value as $key2 => $value2) {
						echo Helper_Output::getErrorByCode($value2);
					}
				} else {
					echo Helper_Output::getErrorByCode($value);
				}
			}
			echo '</div>';
		}
	}

	public static function set_flashData($alias, $data)
	{
		Session::instance()->set($alias, $data);
	}

	public static function flashData($alias)
	{
		return Session::instance()->get_once($alias);
	}

	public static function getval($value)
	{
		if(isset($value)) {
			return $value;
		} else {
			return '';
		}
	}

	public static function getErrorByCode($code)
	{
		$config = Kohana::$config->load('errors');
		$config = $config->as_array();

		if(isset($config[$code])) {
			return $config[$code];
		} else {
			return $code;
		}
	}

	public static function getErrorCode($error)
	{
		$config = Kohana::$config->load('errors');
		$config = $config->as_array();
		$res 	= array_search($error, $config);
		if($res === false) {
			return $error;
		} else {
			return $res;
		}

	}

	public static function siteDate($date) {
		if(!$date) {
			return '';
		} else {
			return date(Kohana::$config->load('config')->get('date.format'), $date);
		}
	}
        
        public static function siteDateForOldDates($date){
                      if($date == '0000-00-00' || !$date) {
                        return DateTime::CreateFromFormat('Y-m-d', date('Y-m-d', time()))->format(Kohana::$config->load('config')->get('date.format'));
                } else {
                        return DateTime::CreateFromFormat('Y-m-d', $date)->format(Kohana::$config->load('config')->get('date.format'));
                }
        }
        
        public static function siteDateWithClientTimeZone($date){
          $offset = Session::instance()->get('timezone_offset');
          return date('l, F jS g:i a', strtotime($date) + $offset);
        }


        public static function getAge($dob)
        {
            $dateIntervalObject = date_diff(date_create($dob), date_create('now'));
            if($dateIntervalObject->y == 0)
                return $dateIntervalObject->days.' days';
            
            return $dateIntervalObject->y.' years';
        }
        
        
        public static function buildUSAPhoneForInputs($phone)
        {
            if($phone)
              return explode('-', $phone);
            else
              return array('','','');
        }

        public static function parseAsTextFieldForOnePoint($res = array())
        {
            $point = new stdClass();
            $matches = array();
            if(is_array($res)){
              if(isset($res['AsText(point)'])) {
                preg_match('/POINT\(([^\)]*)\)/', $res['AsText(point)'], $matches);
              }
            }else{
              preg_match('/POINT\(([^\)]*)\)/', $res, $matches);
            }
            if(isset($matches[1])) {
              $temp = explode(' ', $matches[1]);
              $point->latitude  = $temp[0];
              $point->longitude = $temp[1];
            } else {
              $point->latitude  = 0;
              $point->longitude = 0;
            }
            return $point;
        }
        
        
        public static function getBagesForFeeds($feed_code_name)
        {
            switch ($feed_code_name){
              case('change_pet_status'):
                $html = '<span class="label"> Change Status!</span>';break;
              case('add_pet'):
                $html = '<span class="label label-important">New Pet!</span>';break;
              case('check_in_location'):
                $html = '<span class="label label-success">Check in!</span>';break;
              case('upload_photo'):
                $html = '<span class="label label-info">Photo!</span>';break;
              case('find_pet'):
                $html = '<span class="label label-success">Find Pet!</span>';break;
              case('lost_pet'):
                $html = '<span class="label label-important">Lost Pet!</span>';break;
              default :
                $html = '';break;
            }
            echo $html;
        }
        
}