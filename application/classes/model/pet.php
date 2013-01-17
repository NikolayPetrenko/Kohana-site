<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet extends ORM 
{
	protected $_belongs_to = array(
                                        'owner'    => array(
                                                            'model'             => 'user',
                                                            'foreign_key' 	=> 'user_id',
                                                            ),
                                        'type'     => array('model' => 'pet_type'),
                                        'breed'    => array('model' => 'breed')
                                      );
        
        protected $_has_many = array('locations'     => array('model'        => 'location', 
                                                              'through'      => 'location_checkins',
                                                              'foreign_key'  => 'pet_id',
                                                              'far_key'      => 'location_id'),
                                     'photos'        => array('model'        => 'pet_photo', 
                                                              'foreign_key'  => 'pet_id'),
                                     'finds'         => array('model'        => 'pet_find', 
                                                              'foreign_key'  => 'pet_id')
                                    );


        protected $_has_one = array(
                                    'tag'          => array('model' => 'pet_tag', 'foreign_key' => 'pet_id'),
                                    'lost'         => array('model' => 'pet_lost', 'foreign_key' => 'pet_id')
                                   );


        public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
			'type_id' => array(
				array('not_empty'),
			),
			'breed_id' => array(
				array('not_empty'),
			),
			'user_id' => array(
				array('not_empty')
			),
                        'description' => array(
				array('not_empty')
			),
			'dob' => array(
				array('date')
			)
		);
	}
        
        
        public static function getSuggestedFriends($my_id, $offset = 0, $limit = 8)
        {
            return DB::select(
                              'pets.id',
                              'pets.name',
                              'pets.picture'
                              )
                              ->from('pets')
                              ->join('users')->on('users.id', '=', 'pets.user_id')
                              ->join('friendships', 'RIGHT')->on('friendships.friend_id', '=', 'users.id')
                              ->where('pets.picture', '!=', null)
                              ->where('friendships.accepted', '=', 1)
                              ->where('friendships.user_id', '=', $my_id)
                              ->limit($limit)->offset($offset)
                              ->order_by(DB::expr('RAND()'))
                              ->as_object(get_class())->execute()->as_array();
        }


        public static function getAllMyFriendsPets($my_id, $offset = 0, $limit = 10, $sSearch = '')
        {   
            $res = DB::select(
                              'pets.id',
                              'pets.name',
                              'pets.picture',
                              'users.firstname',
                              'users.lastname'
                              )
                              ->from('users')
                              ->join('friendships', 'RIGHT')->on('friendships.friend_id', '=', 'users.id')
                              ->join('pets', 'RIGHT')->on('users.id', '=', 'pets.user_id')
                              ->where('friendships.accepted', '=', 1)
                              ->where('friendships.user_id', '=', $my_id);
            
            if($sSearch != '')
              $res->where('pets.name', 'like', $sSearch.'%')->or_where('users.firstname', 'like', $sSearch.'%');
          
            $finalres = $res->group_by('pets.id')->limit($limit)->offset($offset)->execute()->as_array();
            
            foreach ($finalres as $key => $item){
              if($finalres[$key]['picture'])
                  $finalres[$key]['picture'] = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2) . $item['id'] . '/' . $item['picture'] ;
            }
            
            
            return $finalres;
        }
        
        public static function searchAllPets($my_id, $limit = 10, $offset = 0, $sType = 'pets')
        {   
            
            $request = Request::initial();
            $res = DB::select(
                              'pets.id',
                              'pets.name',
                              'pets.picture',
                              'users.firstname',
                              'users.lastname',
                              array('users.id', 'owner_id')
                              )
                              ->from('pets')
                              ->join('users')->on('users.id', '=', 'pets.user_id')
                              ->where('pets.user_id', '!=', $my_id)
                              ->where('users.id', '!=', $my_id);
            
            if($sType == 'pets'){
              
                if($request->post('sSearch'))
                    $res->where('pets.name', 'like', $request->post('sSearch').'%');
                
                if($request->post('type_id'))
                    $res->where('pets.type_id', '=', $request->post('type'));
                
                if($request->post('breed'))
                    $res->where('pets.breed_id', '=', $request->post('breed'));
                
            }
            
            if($sType == 'users'){
              
                if($request->post('sSearch'))
                    $res->where('users.firstname', 'like', $request->post('sSearch').'%');
                
                if($request->post('email'))
                    $res->where('users.email', 'like', $request->post('email').'%');
                
                if($request->post('provider'))
                {
                    if($request->post('provider') == 'twitter')
                        $res->where('users.twitter_id', '!=', null);
                    if($request->post('provider') == 'facebook')
                        $res->where('users.facebook_id', '!=', null);
                }
            }
          
            $res = $res->limit($limit)->offset($offset)->execute()->as_array();
            
            
            foreach ($res as $key => $item){
                if($res[$key]['picture'])
                  $res[$key]['picture'] = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2) . $item['id'] . '/' . $item['picture'] ;
                $friendship    = ORM::factory('friendship')->get_friendship_status($item['owner_id'], $my_id);
                if($friendship)
                  $res[$key]['friendship']   = $friendship->accepted;
                else
                  $res[$key]['friendship']   = null;
            }
            return $res;
        }

        
        public static function alerts($my_id, $latitude, $longitude, $status = false , $radius = 80){
                $string = "distance(point, PointFromText('POINT($latitude $longitude)')) * 100";
                
                $res = DB::select('alerts.*')
                          ->select(array(DB::expr('AsText(point)'), 'point'))
                          ->select(array(DB::expr($string), 'distance'))
                          ->from('alerts')
                          ->having('distance', '<', $radius);
                
                if($status)
                  $res->where('status', '=', $status);
                
                $res = $res->order_by(DB::expr("FIELD(friend_id, $my_id)"), "DESC")->as_object()->execute()->as_array();
                
          foreach ($res as $item){
              if($item->status == 'lost'){
                    $pet           = ORM::factory('pet', $item->about_id);
                    $item->id      = $pet->id;
                    $item->name    = $pet->name;
                    $item->point   = Helper_Output::parseAsTextFieldForOnePoint($item->point);
                    if($pet->picture)
                        $item->picture = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2) . $pet->id . '/' . $pet->picture;
                    
              }elseif ($item->status == 'find'){      
                    $find          = ORM::factory('pet_find', $item->about_id);
                    $item->id      = $find->id;
                    $pet           = $find->pet;
                    $item->name    = $pet->name;
                    $item->point   = Helper_Output::parseAsTextFieldForOnePoint($item->point);
                    if($pet->picture)
                        $item->picture = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2) . $pet->id . '/' . $pet->picture;
                    
              }elseif ($item->status == 'unknown') {
                    $unknown           = ORM::factory('user_unknown', $item->about_id);
                    $item->id          = $unknown->id;
                    $item->user_id     = $unknown->user_id;
                    $item->description = $unknown->description;
                    $item->address     = $unknown->address;
                    $item->point       = Helper_Output::parseAsTextFieldForOnePoint($item->point);
                    if($unknown->picture)
                        $item->picture = URL::base().substr(Kohana::$config->load('config')->get('unknown.pets.pics'), 2) . $unknown->picture;
              }
              
              unset($item->friend_id);
              unset($item->about_id);
              
          }
          return $res;
        }
        
        
        
        public static function web_alerts($my_id, $status = false, $limit = 10 , $offset = 0, $count = false){
          $res = DB::select('alerts.*')->from('alerts');
          
          if($status)
            $res = $res->where ('status', '=', $status);
            
          $res = $res->order_by(DB::expr("FIELD(friend_id, $my_id)"), "DESC");
          
          if($limit)
            $res = $res->limit($limit)->offset($offset);
          
          if($count)
            $res = $res->execute()->count();
          else
            $res = $res->as_object()->execute()->as_array();
          
          
          return $res;
        }
        
        
        
}