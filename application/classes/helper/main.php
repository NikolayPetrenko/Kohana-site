<?php
class Helper_Main
{
	static function print_flex($data = '')
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
        
        public static function formatMoney($number) {
            while (true) {
                $replaced = preg_replace('/(-?\d+)(\d\d)/', '$1,$2', $number);
                if ($replaced != $number) {
                    $number = $replaced;
                } else {
                    break;
                }
            }
            return "$".$number;
        } 
}

class Helper_Alert
{
        static $status    = 'success';
        static $strong    = 'Well done!';
          
        static function setStatus($status)
        {
            self::$status = $status;
            switch (self::$status){
              case ('success'):
                self::$strong = 'Well done!'; break;
              case ('error'):
                self::$strong = 'Oh snap!'; break;
              case ('info'):
                self::$strong = 'Heads up!'; break;
              default :
                self::$strong = 'Well done!'; break;
            }
        }
        
        static function get_flash()
        {         $message = Session::instance()->get_once('message');
                  $strong = Session::instance()->get_once('strong');
                  $status = Session::instance()->get_once('status');
                  if(!empty($message))
                    echo '<div class="alert alert-'.$status.'"><button type="button" class="close" data-dismiss="alert">×</button><strong>'.$strong.'</strong> '.$message.'</div>';
        }

        static function set_flash($message)
        {
            Session::instance()->set('message', $message);
            Session::instance()->set('strong',  self::$strong);
            Session::instance()->set('status',  self::$status);
        }
}

class Helper_AdminSiteBar
{
  
    static public $items = array();
    
    static public function init($array = array())
    {
            if(!empty($array)) {
                
                    foreach ($array as $key=>$item) {
                            $obj = new stdClass();
                            $obj->title 	= isset($item['title'])   ? $item['title']          : '';
                            $obj->url 		= isset($item['url'])     ? URL::site($item['url']) : '#';
                            $obj->status	= isset($item['status'])  ? $item['status']         : '';
                            $obj->icon          = isset($item['icon'])    ? $item['icon']           : '';
                            $obj->status        = isset($item['status'])  ? $item['status']         : 0;
                            self::addItem($key, $obj);
                    }
            }
    }
    
    static public function addItem($index = "", stdClass $item)
    {
            if($index != "") {
                    self::$items[$index] = $item;
            }
    }
    
    
    static function setActiveItem($alias)
    {
            if(!empty(self::$items)) {
                    foreach (self::$items as $key=> $item) {
                            if($key != $alias) {
                                    self::$items[$key]->status = 0;
                            } else {
                                    self::$items[$key]->status = 1;
                            }
                    }
            }
    }
  
  
    public static function render(){
      if(!empty(self::$items)) {
          $html = '<ul class="nav nav-list">';
            foreach (self::$items as $key=>$value) {
                  if($value->status == 1)
                    $html .= '<li class="active"><a href="'.$value->url.'"><i class="'.$value->icon.'"></i>'.$value->title.'</a></li>';
                  else
                    $html .= '<li ><a href="'.$value->url.'"><i class="'.$value->icon.'"></i>'.$value->title.'</a></li>';
            }
            $html .= '</ul>';
          echo $html;
        }
    }
}


class Helper_Tab
{
    static public $items = array();
    
    static public function init($array = array())
    {
            if(!empty($array)) {
                
                    foreach ($array as $key=>$item) {
                            $obj = new stdClass();
                            $obj->title 	= isset($item['title'])   ? $item['title']          : '';
                            $obj->url 		= isset($item['url'])     ? URL::site($item['url']) : '#';
                            $obj->status        = isset($item['status'])  ? $item['status']         : 0;
                            self::addItem($key, $obj);
                    }
            }
    }
    
    static public function setPropertyForTab($tabname, $propery, $value)
    {
        if(!empty(self::$items)) {
                  foreach (self::$items as $key=> $item) {
                          if($key == $tabname) {
                                  self::$items[$key]->$propery = $value;
                          }
                  }
          }
    }


    static public function addItem($index = "", stdClass $item)
    {
            if($index != "") {
                    self::$items[$index] = $item;
            }
    }
    
    
    static function setActiveItem($alias)
    {
            if(!empty(self::$items)) {
                    foreach (self::$items as $key=> $item) {
                            if($key != $alias) {
                                    self::$items[$key]->status = 0;
                            } else {
                                    self::$items[$key]->status = 1;
                            }
                    }
            }
    }
    
    public static function disable()
    {
            if(!empty(self::$items)) {
                    foreach (self::$items as $key=> $item) {
                          self::$items[$key]->status = 0;
                    }
            }
    }
    
    public static function disableItem($item)
    {
      unset(self::$items[$item]);
    }

    public static function render(){
      if(!empty(self::$items)) {
          $html = '<ul class="nav nav-tabs">';
            foreach (self::$items as $key=>$value) {
                  if($value->status == 1){
                      if($key == 'unleashed' && isset($value->invites_count))
                          $html .= '<li class="active"><a href="' . $value->url . '" data-toggle="link">'.$value->title.' <span class="badge badge-success">'.$value->invites_count.'</span></a></li>';
                      else
                          $html .= '<li class="active"><a href="' . $value->url . '" data-toggle="link">'.$value->title.'</a></li>';
                  }else{
                      if($key == 'unleashed' && isset($value->invites_count))
                          $html .= '<li><a href="' . $value->url . '" data-toggle="link">'.$value->title.' <span class="badge badge-success">'.$value->invites_count.'</span></a></li>';
                      else
                          $html .= '<li><a href="' . $value->url . '" data-toggle="link">'.$value->title.'</a></li>';
                  }
            }
            $html .= '</ul>';
          echo $html;
        }
    }
  
  
}
