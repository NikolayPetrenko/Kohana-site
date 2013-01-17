<?php
class Helper_Input
{
    public static function changeDateFormat($date)
    {
          $newDate = date_parse_from_format(Kohana::$config->load('config')->get('date.format'), $date);
          return $newDate['year'].'-'.$newDate['month'].'-'.$newDate['day'];
    }
    
    public static function hightLight($what, $where)
    {
          return str_replace($what, "<b>" . $what . "</b>", $where);
    }
    
    public static function buildUSAPhoneInQueryForDBInsert($phone = array())
    {     
          if(!empty($phone))
            return $phone[0].'-'.$phone[1].'-'.$phone[2];
          else
            return '';
    }
    
    public static function xss_clean($str)
    {
      return htmlspecialchars (stripslashes ($str));
    }
    
    
}