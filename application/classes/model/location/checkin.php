<?php defined('SYSPATH') or die('No direct script access.');
//этот клас был создан просто для тестирования и обучения
class Model_Location_Checkin extends ORM 
{
  
//    public static function updateTotal($loc_id, $user_id)
//    {
//        $visit = ORM::factory('location_checkin')->where('location_id', '=', $loc_id)->where('user_id', '=', $user_id)->find();
//        $total = $visit->total + 1;
//        DB::update('location_checkins')
//          ->set(array('total' => $total))
//          ->where('user_id', '=', $user_id)
//          ->where('location_id', '=', $loc_id)
//          ->execute();
//    }
  
    public function setOwner($location_id, $pet_id, $user_id)
    {
        return DB::update($this->table_name())
                 ->set(array('user_id' => $user_id))
                 ->where('location_id', '=', $location_id)
                 ->where('pet_id', '=', $pet_id)
                 ->execute();
    }
    
    public function findMyPetsIDs($location_id, $user_id)
    {
        $res = DB::select('pet_id')
                 ->from($this->table_name())
                 ->where('location_id', '=', $location_id)
                 ->where('user_id', '=', $user_id)
                 ->as_object()->execute()->as_array();
        $IDs = array();
        foreach ($res as $id){
          $IDs[] = $id->pet_id;
        }
        return $IDs;
        
    }
  
}