<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Friends extends My_LoggetUserController 
{
        public function before() {
          parent::before();
          Helper_MainMenuHelper::setActiveItem('friends');
        }

        public function action_unleashed()
        {
          Helper_Tab::setActiveItem('unleashed');
          $limit = Kohana::$config->load('config')->get('lists.count');
          
          $data['friends']                    = $this->logget_user->friends
                                                          ->where('accepted', '=', 1)
                                                          ->limit($limit)
                                                          ->offset(0)
                                                          ->find_all();
          $data['requests_for_me']            = $this->logget_user->friends
                                                          ->where('accepted', '=', 0)
                                                          ->limit($limit)
                                                          ->offset(0)
                                                          ->find_all();
          $data['my_request']                 = ORM::factory('friendship')->getMyRequests($this->logget_user->id, $limit, 0);
          
          $data['requests_for_me_count']      = $this->logget_user->friends->where('accepted', '=', 0)->count_all();
          $data['friends_count']              = $this->logget_user->friends->where('accepted', '=', 1)->count_all();
          $data['my_request_count']           = ORM::factory('friendship')->getMyRequests($this->logget_user->id, false, false, true);
          
          Helper_Output::factory()->link_js('frontend/friends/unleashed');
          $this->setTitle("My Unleashed Friends")
                   ->view('friends/unleashed', $data)
                   ->render()
                   ;
        }
        
        public function action_ajax_get_more_unleashed_friends()
        {
          $friends = $this->logget_user->friends->where('accepted', '=', 1)
                                                        ->limit(Kohana::$config->load('config')->get('lists.count'))
                                                        ->offset($this->request->post('offset'))
                                                        ->find_all();
          $flag = false;
          $next = $this->request->post('offset') + Kohana::$config->load('config')->get('lists.count');
          if ($next >= $this->logget_user->friends->where('accepted', '=', 1)->count_all())
              $flag = true;
          
          Helper_JsonResponse::addData(array( 
                                              'flag' => $flag,
                                              'html'  => View::factory('friends/partial/unleashed_fr_part')->set('friends', $friends)->render()
                                            ));
          Helper_JsonResponse::addText('success');
          Helper_JsonResponse::render();
        }
        
        public function action_ajax_get_more_invites_for_me_friends()
        {
          $requests_for_me = $this->logget_user->friends->where('accepted', '=', 0)
                                                        ->limit(Kohana::$config->load('config')->get('lists.count'))
                                                        ->offset($this->request->post('offset'))
                                                        ->find_all();
          $flag = false;
          $next = $this->request->post('offset') + Kohana::$config->load('config')->get('lists.count');
          if ($next >= $this->logget_user->friends->where('accepted', '=', 0)->count_all())
              $flag = true;
          
          Helper_JsonResponse::addData(array('html'  => View::factory('friends/partial/invites_for_me_fr_part')->set('requests_for_me', $requests_for_me)->render(),
                                             'flag' => $flag)
                                      );
          Helper_JsonResponse::addText('success');
          Helper_JsonResponse::render();
        }
        
        public function action_ajax_get_more_my_invites()
        {
            $data['my_requests'] = ORM::factory('friendship')->getMyRequests($this->logget_user->id, Kohana::$config->load('config')->get('lists.count'), $this->request->post('offset'));
            $flag = false;
            $next = $this->request->post('offset') + Kohana::$config->load('config')->get('lists.count');
            if ($next >= ORM::factory('friendship')->getMyRequests($this->logget_user->id, false, false, true))
                $flag = true;
            Helper_JsonResponse::addData(array(
                                                'flag'  => $flag,
                                                'html'  => View::factory('friends/partial/my_invites_fr_part')->set('my_request',  $data['my_requests'])->render()));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_twitter()
        {
            Helper_Tab::setActiveItem('twitter');
            try{
            $twitter = Helper_Twitter::instance($this->logget_user->twitter_token, $this->logget_user->twitter_secret);
            }catch (EpiTwitterNotAuthorizedException $e){
                $this->logget_user->set('twitter_secret', NULL)->set('twitter_token', NULL)->update();
                $this->request->redirect(URL::site('settings'));
            }
            try
            {
              $data['followers'] = $twitter->getFollowers($this->logget_user->id, -1);
            }  catch (ErrorException $e){
              $data['followers'] = array();
              Helper_Output::addErrors('You have reached the limit of connections. Reset Limit Time : '.$twitter->getLimitStatus());
            }

            Helper_Output::factory()->link_js('frontend/friends/twitter');
            $this->setTitle("My Twitter Followers")
                    ->view('friends/twitter', $data)
                    ->render()
                    ;
        }
        
        public function action_ajaxTwitterFriendsPart()
        {
           $followers = Helper_Twitter::instance($this->logget_user->twitter_token, $this->logget_user->twitter_secret)->getFollowers($this->logget_user->id, $this->request->post('cursor'));
           Helper_JsonResponse::addData(array('cursor' => $followers['next_cursor'],
                                              'html'   => View::factory('friends/partial/twitter_friends')->set('followers', $followers)->render()));
           Helper_JsonResponse::addText('success');
           Helper_JsonResponse::render();
        }




        public function action_facebook()
        {
            Helper_Tab::setActiveItem('facebook');
            $access_token             = $this->logget_user->facebook_token;
            $data['fb_friends']       = Helper_Facebook::instance()->getFriends($access_token, Kohana::$config->load('config')->get('lists.count'), 0, $this->logget_user->id);
            $data['fb_friends_count'] = Helper_Facebook::instance()->getFriendsNumber($this->logget_user->facebook_token);
            Helper_Output::factory()->link_js('http://connect.facebook.net/en_US/all.js')
                                    ->link_js('frontend/friends/facebook');
            $this->setTitle("My Facebook Friends")
                ->view('friends/facebook', $data)
                ->render()
                ;
        }
        
        public function action_ajaxFacebookFriendsPart()
        {
           $fb_friends   = Helper_Facebook::instance()->getFriends($this->logget_user->facebook_token, 
                                                                   Kohana::$config->load('config')->get('lists.count'), 
                                                                   $this->request->post('offset'), 
                                                                   $this->logget_user->id);
           $flag = false;
           $next = $this->request->post('offset') + Kohana::$config->load('config')->get('lists.count');
           if ($next >= Helper_Facebook::instance()->getFriendsNumber($this->logget_user->facebook_token))
              $flag = true;

           Helper_JsonResponse::addData(array('flag' => $flag,
                                              'html' => View::factory('friends/partial/facebook_friends')->set('fb_friends', $fb_friends)->set('me', $this->logget_user)->render()));
           Helper_JsonResponse::addText('success');
           Helper_JsonResponse::render();
        }
        
        public function action_users()
        {
            Helper_Tab::setActiveItem('unleashed_users');
            $users = ORM::factory('user')->where('id', '!=', $this->logget_user->id)->limit(Kohana::$config->load('config')->get('lists.count'))->offset(0)->find_all();
            $data['users_count'] = ORM::factory('user')->where('id', '!=', $this->logget_user->id)->count_all();
            foreach ($users as $key=>$user){
                $data['users'][$key]   =  (object)$user->as_array();
                $friendship = ORM::factory('friendship')->get_friendship_status($user->id, $this->logget_user->id);
                if($friendship)
                {
                  $data['users'][$key]->friendship   = $friendship->accepted;
                }
                else
                {
                    $data['users'][$key]->friendship   = null;
                }
            }
            Helper_Output::factory()->link_js('frontend/friends/users');
            $this->setTitle("Unleashed Users")
                      ->view('friends/users', $data)
                      ->render()
                      ;
        }
        
        public function action_get_more_users()
        {
            $people = ORM::factory('user')->where('id', '!=', $this->logget_user->id)->limit(Kohana::$config->load('config')->get('lists.count'));
            if($this->request->post('offset'))
              $people->offset($this->request->post('offset'));
            if($this->request->post('q'))
              $people->where('email', 'like', mysql_real_escape_string ($this->request->post('q').'%'));
            $people = $people->find_all();
            
            $people_cout = ORM::factory('user')->where('id', '!=', $this->logget_user->id);
            if($this->request->post('q'))
              $people_cout->where('email', 'like', mysql_real_escape_string ($this->request->post('q').'%'));
            $people_cout = $people_cout->count_all();
            
           $flag = false;
           if ($this->request->post('offset') + Kohana::$config->load('config')->get('lists.count') >= $people_cout)
              $flag = true;
            
            
            $users = array();
            foreach ($people as $key=>$user){
                $users[$key]   =  (object)$user->as_array();
                $friendship = ORM::factory('friendship')->get_friendship_status($user->id, $this->logget_user->id);
                if($friendship)
                {
                    $users[$key]->friendship   = $friendship->accepted;
                }
                else
                {
                    $users[$key]->friendship   = null;
                }
            }
            
            Helper_JsonResponse::addData(array('flag' => $flag,
                                               'html'  => View::factory('friends/partial/users_fr_part')->set('users', $users)->render()));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        

        public function action_ajax_send_twitter_invite()
        {
            $twitterObj = new EpiTwitter( Kohana::$config->load('social')->get('twitter.consumer.key'), Kohana::$config->load('social')->get('twitter.consumer.secret'), $this->logget_user->twitter_token, $this->logget_user->twitter_secret);
            $twitterObj->useAsynchronous(true);
            $twitterObj->post('/direct_messages/new.json', 
                              array('user' => $this->request->post('screen_name'), 
                              'text' => "Hey {$this->request->post('screen_name')} , what's up? Join us at Unleashed! "));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        //FriendShip System

        public function action_add_friend()
        {   
            Model_Friendship::addInFriend($this->logget_user);
        }
        
        public function action_accept_friend()
        {
            Model_Friendship::acceptFriendShip($this->logget_user, $this->request->post('id'));
        }
        
        public function action_reject_friend()
        {    
            Model_Friendship::reject_friendship($this->logget_user, $this->request->post('id'));
        }

        public function action_cancel_friend()
        {    
            Model_Friendship::cancel_friend($this->logget_user, $this->request->post('id'));
        }
        
        public function action_delete_friend()
        {   
            Model_Friendship::delete_friend($this->logget_user, $this->request->post('id'));
        }
        
}