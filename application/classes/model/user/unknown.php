<?php defined('SYSPATH') or die('No direct script access.');
//этот клас был создан просто для тестирования и обучения
class Model_User_Unknown extends ORM 
{
  
      protected $_belongs_to = array('user' => array('model' => 'user'));

      
      
      public function getPoint($id){
        $res = $this->select(DB::expr('AsText(point)'))->where('id', '=', $id)->find()->as_array();
        return Helper_Output::parseAsTextFieldForOnePoint($res);
      }


      public function setPoint($values)
      {
          $db = Database::instance();
          if(isset($values['picture']) && $values['picture']){
              @copy(Kohana::$config->load('config')->get('temp.upload').$values['picture'], Kohana::$config->load('config')->get('unknown.pets.pics').$values['picture']);
              @unlink(Kohana::$config->load('config')->get('temp.upload').$values['picture']);
          }
          
          $values['latitude']  = @$values['latitude']  ? $values['latitude']  : 0;
          $values['longitude'] = @$values['longitude'] ? $values['longitude'] : 0;

          $sql = 'INSERT INTO user_unknowns (user_id, picture, description, address, point) 
                  VALUES(' . $db->escape($values['user_id'])       . ', 
                         ' . $db->escape(@$values['picture'])       . ', 
                         ' . $db->escape(@$values['description'])   . ', 
                         ' . $db->escape(@$values['address'])       .
                         ', PointFromText("POINT('.  $values['latitude']  .' '. $values['longitude'].')"))';
          return DB::query(Database::INSERT, $sql)->execute();
      }
  
}