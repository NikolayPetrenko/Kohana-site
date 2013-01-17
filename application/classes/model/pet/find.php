<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet_Find extends ORM 
{
    protected $_belongs_to = array('pet' => array('model' => 'pet'),
                                   'user' => array('model' => 'user')
                                  );
    
    public function getPoint($id)
    {
        $res = $this->select(DB::expr('AsText(point)'))->where('id', '=', $id)->find()->as_array();
        return Helper_Output::parseAsTextFieldForOnePoint($res);
    }

    public function checkIssetLostData($pet_id)
    {
        return DB::select()->from($this->table_name())->where('pet_id', '=', $pet_id)->execute()->count();
    }

    public function setDetails($values, $pet_id)
    {
        if($this->checkIssetLostData($pet_id)){
          $this->update_find_data ($values, $pet_id);
          return $pet_id;
          
        }else{
          
          return $this->insert_find_data ($values, $pet_id);
        }
    }
    
    public function clearDetails($pet_id)
    {
        DB::delete($this->table_name())->where('pet_id', '=', $pet_id)->execute();
    }

    public function insert_find_data($values, $pet_id)
    {
        $values['latitude']  = @$values['latitude']  ? $values['latitude']  : 0;
        $values['longitude'] = @$values['longitude'] ? $values['longitude'] : 0;
        $db = Database::instance();
        $sql = 'INSERT INTO '.$this->table_name().' (pet_id, user_id, address, point) 
                VALUES(' . $db->escape($pet_id)                        . ', 
                       ' . $db->escape($values['user_id'])             . ',
                       ' . $db->escape($values['address'])             .
                       ', PointFromText("POINT('.  $values['latitude']  .' '. $values['longitude'].')"))';
        return DB::query(Database::INSERT, $sql)->execute();
    }
    
    public function update_find_data($values, $pet_id)
    {
        $db = Database::instance();
        $sql = 'UPDATE '.$this->table_name().' SET 
                                                  address          = ' . $db->escape($values['address'])       . ', 
                                                  point            = PointFromText("POINT(' .  $values['latitude']  .' '. $values['longitude']. ')")
                                               WHERE
                                                  pet_id = '.$db->escape($pet_id);
        return DB::query(Database::UPDATE, $sql)->execute();
    }
    
}