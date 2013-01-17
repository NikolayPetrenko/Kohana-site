<?php
class Helper_Pet
{ 
  
      public static function tryCreateFolder($pet_id){
        if(!is_dir(Kohana::$config->load('config')->get('pets.pictures').$pet_id))
          @mkdir(Kohana::$config->load('config')->get('pets.pictures').$pet_id, 0777, TRUE);
      }


      public static function move_pet_file($pet_id, $picture)
      {
        self::tryCreateFolder($pet_id);
        @copy(Kohana::$config->load('config')->get('temp.upload').$picture, Kohana::$config->load('config')->get('pets.pictures').$pet_id.'/'.$picture);
        @unlink(Kohana::$config->load('config')->get('temp.upload').$picture);
      }
      
      
      public static function getAlertDetailsByType($type, $id)
      {
          $petObjectForMobile = '';
          switch ($type){
              case ('lost'):
                $pet = ORM::factory('pet', $id);
                if($pet->lost->pet_id){
                  $petObjectForMobile = new stdClass();
                  $petObjectForMobile->name          = $pet->name;
                  $petObjectForMobile->picture    = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture;
                  $petObjectForMobile->age           = Helper_Output::getAge($pet->dob);
                  $petObjectForMobile->type          = $pet->type->type;
                  $petObjectForMobile->owner_id      = $pet->owner->id;
                  $petObjectForMobile->owner         = $pet->owner->firstname;
                  $petObjectForMobile->breed         = $pet->breed->breed;
                  $petObjectForMobile->description   = $pet->description;
                  $petObjectForMobile->address       = $pet->lost->last_seen;
                  $petObjectForMobile->point         = $pet->lost->getPoint();  
                }
                break;
              case ('find'):
                $find = ORM::factory('pet_find', $id);
                if($find){
                  $petObjectForMobile = new stdClass();
                  $pet  = $find->pet;
                  $petObjectForMobile->name          = $pet->name;
                  if($pet->picture)
                    $petObjectForMobile->picture    = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture;
                  $petObjectForMobile->age           = Helper_Output::getAge($pet->dob);
                  $petObjectForMobile->type          = $pet->type->type;
                  $petObjectForMobile->owner_id      = $pet->owner->id;
                  $petObjectForMobile->owner         = $pet->owner->firstname;
                  $petObjectForMobile->breed         = $pet->breed->breed;
                  $petObjectForMobile->description   = $pet->description;
                  $petObjectForMobile->finder_name   = $find->user->firstname . ' ' . $find->user->lastname;
                  $petObjectForMobile->address       = $find->address;
                  $petObjectForMobile->point         = ORM::factory('pet_find')->getPoint($id);
                }
                break;
              case ('unknown'): 
                $unknown                           = ORM::factory('user_unknown', $id);
                if($unknown){
                  $petObjectForMobile = new stdClass();
                  if($unknown->picture)
                    $petObjectForMobile->picture    = URL::base().substr(Kohana::$config->load('config')->get('unknown.pets.pics'), 2).$unknown->picture;
                  $petObjectForMobile->finder_name   = $unknown->user->firstname . ' ' . $unknown->user->lastname;
                  $petObjectForMobile->description   = $unknown->description;
                  $petObjectForMobile->address       = $unknown->address;
                  $petObjectForMobile->point         = ORM::factory('user_unknown')->getPoint($id);
                }
                break;
            }
            
            return $petObjectForMobile;
      }
}
