<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet_Lost extends ORM 
{
    protected $_primary_key = 'pet_id';
    protected $_belongs_to = array('pet' => array('model' => 'pet'));
    
    public function getPoint()
    {
        $res = ORM::factory('pet_lost')->select(DB::expr('AsText(point)'))->where('pet_id', '=', $this->pet_id)->find()->as_array();
        return Helper_Output::parseAsTextFieldForOnePoint($res);
    }
    
    public function checkIssetLostData($pet_id)
    {
        return DB::select()->from($this->table_name())->where('pet_id', '=', $pet_id)->execute()->count();
    }

    public function setDetails($values, $pet_id, $user = false)
    {
        if($this->checkIssetLostData($pet_id))
          $this->update_lost_data ($values, $pet_id);
        else
          $this->insert_lost_data ($values, $pet_id, $user);
    }
    
    public function clearDetails($pet_id)
    {
        DB::delete($this->table_name())->where('pet_id', '=', $pet_id)->execute();
        DB::delete('pet_finds')->where('pet_id', '=', $pet_id)->execute();
        DB::delete('feeds')->where('pet_id', '=', $pet_id)->where('code_name', '=', 'lost_pet')->or_where('code_name', '=', 'find_pet')->execute();
    }

    public function insert_lost_data($values, $pet_id, $user = false)
    {
        $values['latitude']  = @$values['latitude']  ? $values['latitude']  : 0;
        $values['longitude'] = @$values['longitude'] ? $values['longitude'] : 0;
        $db = Database::instance();
        $sql = 'INSERT INTO pet_losts (`pet_id`, `last_seen`, `point`) 
                VALUES(' . $db->escape($pet_id)                     . ', 
                       ' . $db->escape(@$values['last_seen'])        .
                       ', PointFromText("POINT('.  $values['latitude']  .' '. $values['longitude'].')"))';
        
        $last_id = DB::query(Database::INSERT, $sql)->execute();
        
        $post = array('pet_id'             => $pet_id, 
                      'user_id'            => $user->id, 
                      'latitude'           => $values['latitude'], 
                      'longitude'          => $values['longitude'],
                      'facebook_broadcast' => @$values['facebook_broadcast'],
                      'twitter_broadcast'  => @$values['twitter_broadcast']
                      );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URL::site('home/notifications'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
        return $last_id;
    }
    
    public function update_lost_data($values, $pet_id)
    {
        $db = Database::instance();
        $sql = 'UPDATE pet_losts SET 
                                  last_seen        = ' . $db->escape($values['last_seen'])       . ', 
                                  point            = PointFromText("POINT(' .  $values['latitude']  .' '. $values['longitude']. ')")
                              WHERE
                                  pet_id = '.$db->escape($pet_id);
        return DB::query(Database::UPDATE, $sql)->execute();
    }
    
    
    public static function getCountInRadius($latitude, $longitude, $radius)
    {
        $string = "distance(point, PointFromText('POINT($latitude $longitude)')) * 100";
        return DB::select()
                 ->select(array(DB::expr('AsText(point)'), 'point'))
                 ->select(array(DB::expr($string), 'distance'))
                 ->from('alerts')
                 ->having('distance', '<', $radius)
                 ->where('alerts.status', '=', 'lost')
                 ->execute()->count();
    }
    
    public static function setPDF($pet_id, $pdf)
    {
        return DB::update('pet_losts')
                 ->set(array('pdf' => $pdf))
                 ->where('pet_id', '=', $pet_id)
                 ->execute();
    }
    
    
}