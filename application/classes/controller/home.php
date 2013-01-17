<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends my_usercontroller {

        public function before() {
          parent::before();
        }


        public function action_index()
	{
                Helper_MainMenuHelper::setActiveItem('home');
                Helper_Output::factory()->link_js('frontend/home/index');
		$this->setTitle("Home page")
			->view('home')
			->render()
			;
	}
        
        public function action_pet_profile()
        {
            $data['pet'] = ORM::factory('pet', $this->request->param('id'));
            if(!$data['pet']->id)
                $this->request->redirect ($this->request->referrer ());
            
            $data['logget_user'] = Helper_iAuth::instance()->getLoggedUser();
            
            Helper_Output::factory()
                                    ->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                    ->link_js('frontend/home/profile');
            $this->setTitle($data['pet']->name . " profile")
                 ->view('home/profile', $data)
                 ->render()
                 ;
        }
        
        public function action_test()
        {
          $data['hello']         =  'world';
          $token['access_token'] = '123';
          $res = array_merge($data, $token);
          
          Helper_Main::print_flex($res);
          die();
        }

        public function action_thank_you()
        {
              $this->setTitle("Thank You")
                    ->view('home/thank_you')
                    ->render()
                    ;
        }

        public function action_notifications()
        {
            $user = ORM::factory('user', $this->request->post('user_id'));
            $pet  = ORM::factory('pet', $this->request->post('pet_id'));
            if($user){
                //set Feed
                $feed = $user->firstname.' '.$user->lastname.' lost '.$pet->name . '!!';
                Helper_Feed::factory($user->id, $pet->id)->setCodeName('lost_pet')->setFeed($feed)->save();

                //Create PDF
                $config = array(
                      'author'   => $user->firstname.' '.$user->lastname,
                      'title'    => $pet->name,
                      'subject'  => 'Lost pet',
                      'name'     => Text::random().'.pdf', // name file pdf
                );
                try{
                  View_PDF::factory('pdfs/pet_lost', $config)->set('user', $user)->set('pet', $pet)->render();
                  Model_Pet_Lost::setPDF($pet->id, $config['name']);
                }  catch (HTML2PDF_exception $e){
                  //don't generate
                }

                $tokens           = Model_User::getFriendsDeviceTokensInRadius($user->id, $this->request->post('latitude'), $this->request->post('longitude'), 20);
                $iPhoneMessage    = array('body'=> $feed, 'action-loc-key' => 'Show');
                $notificationData = array('pet_id' => $pet->id, 'type' => 'lost');
                Library_Iphonepush::instance()->setTokens($tokens)
                                              ->setData($notificationData)
                                              ->setMessage($iPhoneMessage)
                                              ->openConnect()
                                              ->send()
                                              ->closeConnect();
                
                $text = $user->firstname . " has lost " . $pet->name . " in ".$pet->lost->last_seen.".  Get the Unleashed iPhone/Android App to help find ".$user->firstname."'s pet.  Check it out at www.UnleashedApp.com.";
                
                if($user->facebook_token && $this->request->post('facebook_broadcast'))
                {
                  try{
                  $img = $pet->picture ? URL::base().substr(Kohana::$config->load('congif')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture : '';
                  Helper_Facebook::instance()->sendNotificationForAllFriends($user->facebook_token, array(
                                                                                                          'message'      => $text,
                                                                                                          'picture'      => $img,
                                                                                                          'link'         => URL::site('alerts/details/lost/'.$pet->id),
                                                                                                          'auto_publish' => false,
                                                                                                          'name'         => 'Pet',
                                                                                                          'description'  => 'www.UnleashedApp.com'
                                                                                                        ));
                  }  catch (FacebookApiException $e){
                    //don't send
                  }
                }
                if($user->twitter_token && $user->twitter_secret && $this->request->post('twitter_broadcast'))
                {
                  Helper_Twitter::instance($user->twitter_token, $user->twitter_secret)->sendNotificationForAllFollowers($text);
                }
            }
        }
        
        
        public function action_unknown_notifications()
        {
            $user = ORM::factory('user', $this->request->post('user_id'));
            //$pet  = ORM::factory('pet', $this->request->post('pet_id'));
            if($user){
                //set Feed
                $feed = $user->firstname.' '.$user->lastname.' find unknown pet !';
                Helper_Feed::factory($user->id, null)->setCodeName('unknown_pet')->setFeed($feed)->save();

                $tokens           = Model_User::getFriendsDeviceTokensInRadius($user->id, $this->request->post('latitude'), $this->request->post('longitude'), 20);

                $iPhoneMessage    = array('body'=> $feed, 'action-loc-key' => 'Show');
                $notificationData = array('pet_id' => $pet->id);
                Library_Iphonepush::instance()->setTokens($tokens)
                                              ->setData($notificationData)
                                              ->setMessage($iPhoneMessage)
                                              ->openConnect()
                                              ->send()
                                              ->closeConnect();
                if($user->facebook_token)
                {
                    try{
                        Helper_Facebook::instance()->sendNotificationAboutLost($user->facebook_token, $feed);
                    }  catch (FacebookApiException $e){
                      //don't send
                    }
                }
                if($user->twitter_token && $user->twitter_secret)
                {
                    Helper_Twitter::instance($user->twitter_token, $user->twitter_secret)->sendNotificationAboutLost($feed);
                }
            }
        }
        
}
