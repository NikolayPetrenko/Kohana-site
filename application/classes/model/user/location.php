<?php defined('SYSPATH') or die('No direct script access.');
//этот клас был создан просто для тестирования и обучения
class Model_User_Location extends ORM 
{
      
      public static function setCoordinates($my_id, $latitude, $longitude)
      {
        if(Model_User_Location::checkIssetLocation($my_id))
          Model_User_Location::updateData ($my_id, $latitude, $longitude);
        else
          Model_User_Location::insertData ($my_id, $latitude, $longitude);
      }

      
      
      public static function checkIssetLocation($my_id)
      {
        return DB::select()->from('user_locations')->where('user_id', '=', $my_id)->execute()->count();
      }


      public static function insertData($my_id, $latitude, $longitude)
      {
          $db        = Database::instance();
          $latitude  = @$latitude  ? $latitude  : 0;
          $longitude = @$longitude ? $longitude : 0;
          $sql       = 'INSERT INTO user_locations (user_id, point) 
                        VALUES(' . $db->escape($my_id)  .
                               ', PointFromText("POINT('.  $latitude  .' '. $longitude.')"))';
          return DB::query(Database::INSERT, $sql)->execute();
      }
      
      
      public static function updateData($my_id, $latitude, $longitude)
      {
          $db        = Database::instance();
          $latitude  = @$latitude  ? $latitude  : 0;
          $longitude = @$longitude ? $longitude : 0;
          $sql       = 'UPDATE user_locations SET 
                                              point   = PointFromText("POINT(' .  $latitude  .' '. $longitude. ')")
                                              WHERE
                                              user_id = '.$db->escape($my_id);
          return DB::query(Database::UPDATE, $sql)->execute();
      }
      
      
  
}