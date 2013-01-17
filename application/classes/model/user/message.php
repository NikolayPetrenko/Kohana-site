<?php defined('SYSPATH') or die('No direct script access.');
//этот клас был создан просто для тестирования и обучения
class Model_User_Message extends ORM 
{
    
    protected $_belongs_to = array(
                                        'owner'    => array(
                                                            'model'             => 'user',
                                                            'foreign_key' 	=> 'user_id',
                                                            ),
                                      );
    
    public function rules()
	{
		return array(
			'user_id' => array(
				array('not_empty'),
			),
			'addressee_id' => array(
				array('not_empty'),
			),
			'message' => array(
				array('not_empty'),
			)
		);
	}
    
  
    public static function getAllTreads($user_id){
      
      $sql = "SELECT users.firstname, 
                     users.lastname, 
                     users.avatar, 
                     MAX(date_create) AS last_message_date, 
                     tread_user_id, 
                     last_message
      
              FROM (  SELECT * FROM (SELECT id, 
                                            addressee_id AS tread_user_id,
                                            message AS last_message,
                                            date_create 
                                      FROM user_messages 
                                      WHERE user_id = :user
                                    UNION
                                      SELECT id,  
                                            user_id AS tread_user_id,
                                            message AS last_message,
                                            date_create 
                                      FROM user_messages 
                                      WHERE addressee_id = :user
                      ) vi ORDER BY vi.date_create desc ) user_messages
      
              JOIN  users  ON  users.id = tread_user_id
              GROUP BY tread_user_id
              ORDER BY date_create";
      return DB::query(Database::SELECT, $sql)->param(':user', $user_id)->as_object()->execute()->as_array();

    }
    
    
    public function save_message($values, $expected){
        return $this->values($values, $expected)->create();
    }
  
}