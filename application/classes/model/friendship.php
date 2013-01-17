<?php
class Model_Friendship extends ORM {   
  
      protected $_table = 'friendships';
      

      public function getMyRequests($user_id, $limit, $offset = 0, $count = false)
      {
          $res = DB::select('users.*')
                  ->from($this->_table)
                  ->join('users', 'RIGHT')->on($this->_table.'.friend_id', '=', 'users.id')
                  ->where('user_id', '=', $user_id)
                  ->where('accepted', '=', 0);
          
          if($limit)
              $res = $res->limit($limit)->offset($offset);
          
          if($count){
              $res = $res->execute()->count();
          }else{
              $res = $res->order_by('users.firstname', 'asc')->as_object('Model_User')->execute()->as_array();
          }
            return $res;
      }

      public static function setFriendShipStatus($friend_id, $user_id)
      {
          DB::update('friendships')->set(array('accepted' => true))->where('friend_id', '=', $friend_id)->where('user_id', '=', $user_id)->execute();
      }

      public function get_friendship_status($friend_id, $user_id)
      {
          $friendship = ORM::factory('friendship')->where('user_id', '=', $friend_id)->where('friend_id', '=', $user_id)->find();
          
          if($friendship->accepted !== null)
            return $friendship;
          
          $friendship = ORM::factory('friendship')->where('user_id', '=', $user_id)->where('friend_id', '=', $friend_id)->find();
          
          if($friendship->accepted !== null)
            return $friendship;
      }
      
      public static function addInFriendByPetID($logget_user, $pet_id){
          $pet = ORM::factory('pet', $pet_id);
          $invitee = $pet->owner;
          if(!$invitee->has('friends', $logget_user)){
              $invitee->add('friends', $logget_user);
          }
      }


      public static function addInFriend($logget_user)
      {
            $invitee = ORM::factory('user');
            if(Request::initial()->post('facebook_id'))
                $invitee->where('facebook_id', '=', Request::initial()->post('facebook_id'))->find();
            if(Request::initial()->post('twitter_id'))
                $invitee->where('twitter_id', '=', Request::initial()->post('twitter_id'))->find();
            if(Request::initial()->post('unleashed_id'))
                $invitee->where('id', '=', Request::initial()->post('unleashed_id'))->find();
            
            if(!$invitee->has('friends', $logget_user)){
                $invitee->add('friends', $logget_user);
            }
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
      }
      
      public static function acceptFriendShip($logget_user, $friend_id)
      {
            $friend = ORM::factory('user', $friend_id);
            
            if(!$friend){
                Helper_JsonResponse::addError(Helper_Output::getErrorCode("User doesn't exist"));
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            }
            
            
            if ( ! $friend->has('friends', $logget_user)){
                $friend->add('friends', $logget_user);
            }
            
            Model_Friendship::setFriendShipStatus($friend->id, $logget_user->id);
            Model_Friendship::setFriendShipStatus($logget_user->id, $friend->id);
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
      }
      
      public static function reject_friendship($friend_id){
            $friend = ORM::factory('user', $friend_id);
            if ($friend->has('friends', $user)){
                $friend->remove('friends', $user);
            }
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
      }
      
      public static function cancel_friend($user, $friend_id){
        
            $friend = ORM::factory('user', $friend_id);
            if ($user->has('friends', $friend))
            {
                $user->remove('friends', $friend);
            }
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
      }

      public static function delete_friend($user, $friend_id){
            $friend = ORM::factory('user', $friend_id);

            if ($user->has('friends', $friend) && $friend->has('friends', $user))
            {
                $user->remove('friends', $friend);
                $friend->remove('friends', $user);
                Helper_JsonResponse::addText('success');
                Helper_JsonResponse::render();
            }
      }
  
}

?>