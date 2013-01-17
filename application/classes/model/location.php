<?php defined('SYSPATH') or die('No direct script access.');
//этот клас был создан просто для тестирования и обучения
class Model_Location extends ORM 
{
      protected $_belongs_to = array(
                                  'category'    => array('model' => 'location_category')
                                );
      
      protected $_has_many   = array(
                                     'pets'   => array(
                                                           'model'        => 'pet',
                                                           'through'      => 'location_checkins',
                                                           'foreign_key'  => 'location_id',
                                                           'far_key'      => 'pet_id'
                                                          ),
                                     'confirms'   => array(
                                                           'model'        => 'user',
                                                           'through'      => 'location_confirms',
                                                           'foreign_key'  => 'location_id',
                                                           'far_key'      => 'user_id'
                                                          )
                                    );


      public function __construct($id = NULL) {
        parent::__construct($id);
        $this->select(DB::expr('AsText(point)'));
      }

      public function rules() {
          return array(
                          'name' => array(
                                  array('not_empty'),
                                  array('max_length', array(':value', 60)),
                          ),
                          'category_id' => array(
                                  array('not_empty')
                          )
                  );
      }
      
      public function setPoint($values, $type = 'insert')
      {
        $db = Database::instance();
        if(isset($values['picture']) && $values['picture']){
            @copy(Kohana::$config->load('config')->get('temp.upload').$values['picture'], Kohana::$config->load('config')->get('location.pictures').$values['picture']);
            @unlink(Kohana::$config->load('config')->get('temp.upload').$values['picture']);
        }
        
        if($type == 'insert') {
          $sql = 'INSERT INTO locations (name, description, category_id, picture, address, phone, point) 
                  VALUES(' . $db->escape($values['name'])        . ', 
                         ' . $db->escape($values['description']) . ', 
                         ' . $db->escape($values['category_id']) . ', 
                         ' . $db->escape(@$values['picture'])    . ', 
                         ' . $db->escape($values['address'])     . ', 
                         ' . $db->escape(@$values['phone'])       .
                         ', PointFromText("POINT('.  $values['latitude']  .' '. $values['longitude'].')"))';
              return DB::query(Database::INSERT, $sql)->execute();
        }
        
         if($type == 'update') {
           $sql = 'UPDATE locations SET 
                                       name        = ' . $db->escape($values['name'])        . ', 
                                       description = ' . $db->escape($values['description']) . ', 
                                       category_id = ' . $db->escape($values['category_id']) . ', 
                                       picture     = ' . $db->escape($values['picture'])     . ', 
                                       address     = ' . $db->escape($values['address'])     . ', 
                                       phone       = ' . $db->escape($values['phone'])       . ', 
                                       status      = ' . $db->escape($values['status'])      . ',
                                       point       = PointFromText("POINT(' .  $values['latitude']  .' '. $values['longitude']. ')")
                                    WHERE
                                       id = '.$db->escape($values['id']);
              return DB::query(Database::UPDATE, $sql)->execute();
         }
        
      }
           
      public function getPoint($pointID = null)
      {
          $res = $this->select(DB::expr('AsText(point)'))->where('id', '=', $pointID)->find()->as_array();
          $this->point = Helper_Output::parseAsTextFieldForOnePoint($res);
          return $this;
      }
      
      
      public static function getLocationsNearMe($latitude, $longitude, $radius = 80, $my_id, $category_id = false) // Radius set in kilometres. 80 km ~ 50 ml.
      {   
          $string = "distance(point, PointFromText('POINT($latitude $longitude)')) * 100";
          
          $res = DB::select(
                   '*',
                   DB::expr('AsText(point)'),
                   array(DB::expr($string), 'distance'))
                  ->from('locations')
                  ->having('distance', '<', $radius);
                  
          if($category_id)
            $res = $res->where('category_id', '=', $category_id);
                  
          $locations = $res->order_by('distance')->as_object(get_class())->execute()->as_array();
          $finalRes  = array();
          foreach ($locations as $key=>$item){
              $finalRes[$key]                 = $item->build_location_for_request();
              $finalRes[$key]['confirm']      = $item->confirms->where('user_id', '=', $my_id)->count_all();
              $finalRes[$key]['checkin_pets'] = ORM::factory('location_checkin')->findMyPetsIDs($item->id, $my_id);
          }
          return $finalRes;
      }
      
      
      public function build_location_for_request($distance = true)
      {
          $location['id']           = $this->id;
          $location['name']         = $this->name;
          if($this->picture)
            $location['picture']    = URL::base().substr(Kohana::$config->load('config')->get('location.pictures'), 2).$this->picture;
          $location['description']  = $this->description;
          $location['address']      = $this->address;
          $location['phone']        = $this->phone;
          if($distance)
            $location['distance']     = round($this->distance);
          $location['admin_confirm']= $this->isConfirm;
          $location['all_checkins'] = $this->pets->count_all(); 
          $location['category']     = $this->category->name;
          $location['point']        = Helper_Output::parseAsTextFieldForOnePoint($this->as_array());
          return $location;
      }
      
      
      
}