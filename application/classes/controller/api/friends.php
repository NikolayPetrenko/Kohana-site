<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Friends extends My_ApiController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_getUnleashedFriends()
        {
            $s            = $this->request->post('sSearch');
            $offset       = $this->request->post('offset');
            $limit        = $this->request->post('limit');
            
            $pets = Model_Pet::getAllMyFriendsPets($this->logget_user->id, $offset, $limit, $s);
            Helper_JsonResponse::addData(array('unleashed_friend_pets' => $pets));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        
        public function action_getFacebookFriends()
        {
            try{
                $fb = Helper_Facebook::instance();
                $access_token = $this->request->post('access_token');
                $offset       = $this->request->post('offset');
                $limit        = $this->request->post('limit');

                if($access_token)
                {
                    $fb->facebook->setAccessToken($access_token);
                    $friends = $fb->getFriends($access_token, $limit, $offset);
                    Helper_JsonResponse::addData(array('fb_friends' => $friends));
                    Helper_JsonResponse::addText('success');
                    Helper_JsonResponse::render();
                }
            }  catch (Exception $e ){
                Helper_JsonResponse::addError(Helper_Output::getErrorCode("Unable connect to Facebook"));
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            } 
        }
        
        
        public function action_getTwitterFriends()
        {
            try{
                $twitter_token  = $this->request->post('twitter_token');
                $twitter_secret = $this->request->post('twitter_secret');
                $cursor         = $this->request->post('cursor');
                $twt = Helper_Twitter::instance($twitter_token, $twitter_secret);
                if($twitter_token && $twitter_secret)
                {
                    $friends    = $twt->getFollowers($this->logget_user->id, $cursor);
                    Helper_JsonResponse::addData(array('twt_friends' => $friends));
                    Helper_JsonResponse::addText('success');
                    Helper_JsonResponse::render();
                }
            }  catch (Exception $e ){
                Helper_JsonResponse::addError(Helper_Output::getErrorCode("Could not authenticate with OAuth"));
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            }
        }
        
        public function action_requests(){
          $users = array() ;
          foreach ($this->logget_user->friends->where('accepted', '=', 0)->limit($this->request->post('limit'))->offset($this->request->post('offset'))->find_all() as $key => $item){
            $users[$key]['id']        = $item->id;
            $users[$key]['firstname'] = $item->firstname;
            $users[$key]['lastname']  = $item->lastname;
            if($item->avatar)
              $users[$key]['avatar']  = URL::base ().substr(Kohana::$config->load ('config')->get ('user.avatars'), 2).$item->avatar;
          }
          
          Helper_JsonResponse::addData(array('requests' => $users));
          Helper_JsonResponse::addText('success');
          Helper_JsonResponse::render();
        }

        //FriendShip System

        public function action_addInFriends()
        {   
            Model_Friendship::addInFriend($this->logget_user);
        }
        
        public function action_addInFriendByPetID(){
          $pet = ORM::factory('pet', $this->request->post('pet_id'));
          if($pet->id){
            Model_Friendship::addInFriendByPetID($this->logget_user, $pet->id);
            Helper_JsonResponse::addText('success');
          }else{
            Helper_JsonResponse::addText('failure');
          }
          Helper_JsonResponse::render();
        }


        public function action_acceptFriendShip()
        {
            Model_Friendship::acceptFriendShip($this->logget_user, $this->request->post('id'));
        }
        
        public function action_rejectFriend()
        {    
            Model_Friendship::reject_friendship($this->logget_user, $this->request->post('id'));
        }

        public function action_cancelFriend()
        {    
            Model_Friendship::cancel_friend($this->logget_user, $this->request->post('id'));
        }
        
        public function action_deleteFriend()
        {   
            Model_Friendship::delete_friend($this->logget_user, $this->request->post('id'));
        }
        
}