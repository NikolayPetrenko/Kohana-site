<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Users extends My_UserController 
{
	public function before()
	{
		parent::before();
		Helper_Output::factory()->link_js('jquery.validate.min');
                Helper_Tab::setActiveItem('aboutme');
	}

	public function action_index()
	{
           if($this->request->param('id')){
              $data['profile']  = ORM::factory('user', $this->request->param('id'));
              if(!$data['profile']->id)
                $this->request->redirect('');
              
              
              $data['suggested_friends']  = Model_Pet::getSuggestedFriends($data['profile']->id);
              $data['logget_user']        = Helper_iAuth::instance()->getLoggedUser();
              
              if($data['logget_user']){
                if($this->logget_user->id != $data['profile']->id)
                  Helper_Tab::disable ('aboutme');
                
                $friendship = ORM::factory('friendship')->get_friendship_status($data['profile']->id, $this->logget_user->id);
                if($friendship)
                    $data['relationship'] = $friendship->accepted;
                else
                    $data['relationship'] = null;
              }
              
              $data['pets']          = $data['profile']->pets->find_all();
              Helper_Output::factory()->link_js('frontend/user_profile');
              $this->setTitle($data['profile']->firstname." Profile")
                   ->view('user/user_profile', $data)
                   ->render()
                   ;
           }
	}
        

        public function action_profile($action = 'view')
	{
		
		if(!Helper_iAuth::instance()->isLoggedin() ) {
			$this->request->redirect('');
		}

		Helper_Output::factory()->link_js('jquery.ui.widget')
                                        ->link_js('jquery.fileupload')
					->link_js('frontend/profile')
					->link_js('bootstrap-datepicker')
					->link_css('datepicker')
					;
		if($this->request->method() == 'POST' ) {
			try {
				$user = Helper_iAuth::instance()->getLoggedUser();
                                $_POST['dob'] = Helper_Input::changeDateFormat($_POST['dob']);
				$user->update_user($_POST, array('firstname', 'lastname', 'address', 'primary_phone', 'secondary_phone', 'dob', 'avatar', 'state', 'city', 'zip'));
                                Helper_Alert::set_flash('User info was update');
				return $this->request->redirect('users/profile');
			}
			catch(ORM_Validation_Exception $e) {
				Helper_Output::addErrors($e->errors(''));
			}
		}

		$data = array();
		$data['me'] 	= Helper_iAuth::instance()->getLoggedUser();                                     
                
                $data['timezone_offset'] = Session::instance()->get('timezone_offset');
		if($this->request->param('id')) {
			$data['action']	= $this->request->param('id');
		} else {
			$data['action'] = $action;
		}
                Helper_Output::factory()->link_js('frontend/profile');
		$this->setTitle("User Auth")
			->view('user/profile', $data)
			->render()
			;
	}
        
        public function action_setInSessionUserTimeZone(){
          Session::instance()->set('timezone_offset', $this->request->post('timezone_offset'));
        }



        public function action_login()
	{
		//try to do Facebook Auth if user not logged in and not trying login directly
		if($this->request->method() != 'POST' && ! Helper_iAuth::instance()->isLoggedin()) {
			if(!Helper_iAuth::instance()->facebookAuth() && FacebookAuth::factory()->getMe()) {
				return $this->request->redirect('users/facebook_registration');
			}
		}

		if(Helper_iAuth::instance()->isLoggedin() )	{
			if((isset($_POST['json']) && $_POST['json'] == 1)) {
				Helper_JsonResponse::addError(Helper_Output::getErrorCode('Permission Denied'));
				Helper_JsonResponse::addText('failure');
				Helper_JsonResponse::render();
				return;
			} else {
				return $this->request->redirect('users/profile');
			}
		}

		if($this->request->method() != 'POST') {

			Helper_Output::factory()->link_js('frontend/login');

			$data['prevalue'] = Helper_Output::flashData('prevData');
			$this->setTitle("User Auth")
				->view('user/login', $data)
				->render()
				;
		} else {
			$user 	= ORM::factory('user');
			$status = $user->login($_POST['email'], $_POST['password']);
			if($status) {
				//generate token for user
				Helper_iAuth::instance()->createSession();
				
				if(isset($_POST['json']) && $_POST['json'] == 1) {
					Helper_JsonResponse::addData(array(
								'user_info'  => Helper_iAuth::instance()->getUserData(),
								'sess_token' => Helper_iAuth::$token
								));
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
					return;
				} else {
                                        $current = Helper_iAuth::instance()->getLoggedUser();
                                        if(!empty($current->facebook_token))
                                          Helper_Facebook::instance()->regenerateFacebookToken($current->facebook_token);
					return $this->request->redirect('users/profile');
				}
			} else {
				if(isset($_POST['json']) && $_POST['json'] == 1) {
					foreach (Helper_Output::getErrors() as $key => $value) {
						Helper_JsonResponse::addError($value);
					}
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				} else {
					return $this->request->redirect('users/login');
				}
			}

		}
	}

	public function action_facebook_registration()
	{
		Cookie::delete('fbsr_'.FacebookAuth::factory()->getFacebook()->getAppId());
		// Cookie::delete('session_name');
		if($this->request->method() == 'POST') {
			$res = ORM::factory('user')->createFacebookUser($_POST, array('firstname', 'lastname', 'email', 'termofuse', 'facebook_id', 'facebook_token', 'facebook_expire_date'));
			if(!$res) {
				if(isset($_POST['json'])) {
					foreach (Helper_Output::getErrors() as $key => $value) {
						Helper_JsonResponse::addError($value);
					}
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				} else {
					Helper_Output::keepErrors();
					Helper_Output::set_flashData('prevData', $_POST);
					return $this->request->redirect('users/facebook_registration');
				}
			} else {
				if(!isset($_POST['json'])) {
					return $this->request->redirect('users/profile');
				} else {
					Helper_JsonResponse::addData(array(
                                                                          'user_info'  => Helper_iAuth::instance()->getUserData(),
                                                                          'sess_token' => Helper_iAuth::$token
                                                                          ));
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
				}
			}
		} else {
			$data['fbinfo'] = (object)FacebookAuth::factory()->getMe();
                        $data['fbinfo']->accessToken = FacebookAuth::factory()->getFacebook()->getAccessToken();
			$data['prevalue'] = Helper_Output::flashData('prevData');
			if($data['fbinfo']) {
				$this->setTitle("Facebook Registration")
					->view('user/facebook_registration', $data)
					->render()
					;
			} else {
				return $this->request->redirect('');
			}
		}
	}
	
	public function action_fbloginphone()
	{
		if($this->request->method() == 'POST' && (isset($_POST['json']) && $_POST['json'] == 1) && ($_POST['fb_token'])) {
			if(FacebookAuth::factory()->getConnection($_POST['fb_token'])) {
				if(Helper_iAuth::instance()->facebookAuth()) {
					Helper_JsonResponse::addData(array(
								'user_info'  => Helper_iAuth::instance()->getUserData(),
								'sess_token' => Helper_iAuth::$token
								));
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
					return;	
				} else {
					Helper_JsonResponse::addError(Helper_Output::getErrorCode("User doesn't exist in system"));
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				}	
			} else {
				Helper_JsonResponse::addError(Helper_Output::getErrorCode('Unable connect to Facebook'));
				Helper_JsonResponse::addText('failure');
				Helper_JsonResponse::render();
				return;
			}
		} else {
			$this->request->redirect('');
		}
	}
	
	public function action_twitter_registrate()
	{
		$token 	= Cookie::get('oauth_token');
		$secret = Cookie::get('oauth_token_secret');
                
		if($this->request->method() != 'POST') {
			if($token) {
				$newOject =  new EpiTwitter(
                                                            Kohana::$config->load('social')->get('twitter.consumer.key'), 
                                                            Kohana::$config->load('social')->get('twitter.consumer.secret'),
                                                            $token,
                                                            $secret
                                                            );
				$userInfo = $newOject->get_accountVerify_credentials();
				$data['twitterinfo'] 	= (object) $userInfo->response;
			} 
                        
			//Cookie::delete('oauth_token');
			//Cookie::delete('oauth_token_secret');

			// $data['twitterinfo'] 	= (object) $userInfo->response;
			$data['prevalue'] 		= Helper_Output::flashData('prevData');
                        Helper_Output::factory()->link_js('jquery.validate.min')->link_js('frontend/registration_twitter');
			$this->setTitle("Twitter Registration")
				->view('user/twitter_registration', $data)
				->render()
				;
		} else {
                        $res = ORM::factory('user')->createTwitterUser($_POST, array('firstname', 'lastname', 'email', 'twitter_id', 'twitter_token', 'twitter_secret'));
                        
			if($res) {
				if(isset($_POST['json']) && $_POST['json'] == 1) {
					Helper_JsonResponse::addData(array(
                                                                          'user_info'  => Helper_iAuth::instance()->getUserData(),
                                                                          'sess_token' => Helper_iAuth::$token
                                                                          ));
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
					return;
				} else {
					return $this->request->redirect('users/profile');
				}
			} else {
				if(isset($_POST['json']) && $_POST['json'] == 1) {
					foreach (Helper_Output::getErrors() as $key => $value) {
						Helper_JsonResponse::addError($value);
					}
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				} else {
					Helper_Output::keepErrors();
					Helper_Output::set_flashData('prevData', $_POST);
					return $this->request->redirect('users/twitter_registrate');
				}
			}
			// Helper_Main::print_flex(Helper_Output::getErrors());
			// die();
		}
	}
        
        
        public function action_check_registrate_user()
        {
            $findUser = ORM::factory('user')->where('email', '=', $this->request->post('email'))->find();
            if($findUser->id){
              Helper_JsonResponse::addData(array('status' => 'exist', 
                                                 'html' => View::factory('user/registration_isset_user_form')->set('user', $findUser)->render())
                                          );
            }else{
              Helper_JsonResponse::addData(array('status' => 'not exist' ));
            }
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        
        public function action_check_exist_user_password()
        {
            $user 	= ORM::factory('user');
            $status     = $user->login($this->request->post('email'), $this->request->post('password'));
            if($status){
              ORM::factory('user')
                      ->where('email', '=',$this->request->post('email'))
                      ->find()
                      ->set('twitter_token', $this->request->post('twitter_token'))
                      ->set('twitter_secret', $this->request->post('twitter_secret'))
                      ->set('twitter_id', $this->request->post('twitter_id'))
                      ->update();
              Helper_JsonResponse::addText('success');
            }else{
              Helper_JsonResponse::addError('Wrong email');
              Helper_JsonResponse::addText('failure');
            }
            Helper_JsonResponse::render();
        }

	public function action_twlogin()
	{
          if($this->request->method() != 'POST') {
                          if(isset($_GET['oauth_token']) && $_GET['oauth_token'] != '') {
                                  Helper_iAuth::getTwitter()->setToken($_GET['oauth_token']);
                                  $token = Helper_iAuth::getTwitter()->getAccessToken();  
                                  
                                  try{
                                      $newOject =  new EpiTwitter(
                                                                    Kohana::$config->load('social')->get('twitter.consumer.key'), 
                                                                    Kohana::$config->load('social')->get('twitter.consumer.secret'),
                                                                    $token->oauth_token,
                                                                    $token->oauth_token_secret
                                                          );

                                  }  catch (EpiOAuthException $e){
                                    
                                    Helper_Alert::setStatus('error');
                                    Helper_Alert::set_flash('Problems with Twitter provider');
                                    $this->request->redirect('');

                                  }

                                  $userInfo = $newOject->get_accountVerify_credentials();

                                  $twitterID = $userInfo->response['id'];
                                  $user      = ORM::factory('user')->where('twitter_id', '=', $twitterID)->find();
                                  $userArray = $user->as_array();

                                  Cookie::set('oauth_token',$token->oauth_token,60*60*24*7);
                                  Cookie::set('oauth_token_secret',$token->oauth_token_secret,60*60*24*7);

                                  $currentUser = Helper_iAuth::instance()->getUserData();
                                  
                                  if(!empty($currentUser)){
                                    
                                    $currentUser = ORM::factory('user', $currentUser['id']);
                                    $currentUser->twitter_token       = $token->oauth_token;
                                    $currentUser->twitter_secret      = $token->oauth_token_secret;
                                    $currentUser->update();
                                    return $this->request->redirect('settings');
                                  }elseif(isset($userArray['id']) && $userArray['id']) {
                                          $user->last_login          = time();
                                          $user->logins              = $user->logins+1;
                                          $user->twitter_token       = $token->oauth_token;
                                          $user->twitter_secret      = $token->oauth_token_secret;
                                          $user->update();
                                          
                                          Session::instance()->regenerate();
                                          Session::instance()->set('auth_user', $user);
                                          //generate token for user
                                          Helper_iAuth::instance()->createSession();
                                          
                                          return $this->request->redirect('settings');
                                  }else{
                                          
                                          $this->request->redirect('users/twitter_registrate');
                                  }

                          } else {
                                  $this->request->redirect('');
                          }
		} else {
			
			if(isset($_POST['twitter_token']) && isset($_POST['twitter_secret']) && isset($_POST['json'])) {
				$token 	= $_POST['twitter_token'];
				$secret = $_POST['twitter_secret'];

				// echo (Cookie::get('oauth_token'). '<br>');
				// echo (Cookie::get('oauth_token_secret'));
				// die();

				$newOject =  new EpiTwitter(
								Kohana::$config->load('social')->get('twitter.consumer.key'), 
								Kohana::$config->load('social')->get('twitter.consumer.secret'),
								$token,
								$secret
				);
				try {
					$userInfo = $newOject->get_accountVerify_credentials();
					$user = ORM::factory('user')->where('twitter_id', '=', $userInfo->response['id'])->find();
					$user->last_login 	= time();
					$user->logins		= $user->logins+1;
                                        $user->twitter_token    = $token;
                                        $user->twitter_secret   = $secret;
					$userArray = $user->as_array();

					if(isset($userArray['id']) && $userArray['id']) {
						$user->update();
						Session::instance()->regenerate();
						Session::instance()->set('auth_user', $user);
						//generate token for user
						Helper_iAuth::instance()->createSession();
						
						Helper_JsonResponse::addData(array(
								'user_info'  => Helper_iAuth::instance()->getUserData(),
								'sess_token' => Helper_iAuth::$token
								));
						Helper_JsonResponse::addText('success');
						Helper_JsonResponse::render();
						return;

					} else {
						Helper_JsonResponse::addError( Helper_Output::getErrorCode("User doesn't exist in system") );
						Helper_JsonResponse::addText('failure');
						Helper_JsonResponse::render();
						return;
					}
					// var_dump();die();
				}
				//TODO need to add more catch for different errors
				catch(EpiTwitterNotAuthorizedException $e){
					$error = json_decode($e->getMessage());
					Helper_JsonResponse::addError( Helper_Output::getErrorCode($error->error) );
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;

				}
				
			}

		}	
	}

	public function action_forgot()
	{
		if(Helper_iAuth::instance()->isLoggedin()) {
			return $this->request->redirect('');
		}

		if($this->request->method() == 'POST' && isset($_POST['email'])) {

			$user = ORM::factory('user')->where('email', '=', $_POST['email'])->find();
			if($user->id) {
				$user->sendInterview();
				if(isset($_POST['json'])) {
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
					return;
				} else {
					//show confirm page
					$data['text']	= "Email with confirmation have been sent to ". $_POST['email'] .", please check your incoming messages";
					$this->setTitle("Confirm Forgot you password")
						->view('user/forgot_confirm', $data)
						->render()
						;
				}
			} else {
				if(isset($_POST['json'])) {
					Helper_JsonResponse::addError( Helper_Output::getErrorCode("User doesn't exist in system") );
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				} else {
					Helper_Output::addErrors(Helper_Output::getErrorCode("User doesn't exist in system"));
					Helper_Output::keepErrors();
					return $this->request->redirect('users/forgot');
				}
			}
		} else {
			if(isset($_GET['hash'])) {
				$user = ORM::factory('user')->where('hash_code', '=', $_GET['hash'])->find();
				if($user->id) {
					$user->recoveryPassword();
					$data['text']	= "Email with new password have been sent to your email!";
					$this->setTitle("Confirm Forgot you password")
						->view('user/forgot_confirm', $data)
						->render()
						;
				} else {
					//TODO 404
					return $this->request->redirect('');
				}
			} else {
				Helper_Output::factory()->link_js('frontend/forgotpassword');
				$this->setTitle("Forgot you password")
					->view('user/forgot')
					->render()
					;
			}
		}
	}

	public function action_ajax_email_check()
	{
		if($this->request->method() == 'POST' && isset($_POST['email'])) {
			$user = ORM::factory('user')->where('email', '=', $_POST['email'])->find();
			if($user->id) {
				Helper_JsonResponse::addText('success');
				Helper_JsonResponse::render();
				return;
			} else {
				Helper_JsonResponse::addError( "User doesn't exist in system" );
				Helper_JsonResponse::addText('failure');
				Helper_JsonResponse::render();
				return;
			}
		}
	}

	public function action_logout() 
	{
		if(FacebookAuth::factory()->logged_in()) {
			FacebookAuth::factory()->getFacebook()->destroySession();
			Cookie::delete('session_name');
			return $this->request->redirect(FacebookAuth::factory()->logout_url());
		}
                  try{
                      if(Helper_iAuth::instance()->isTwitterLoggedin() === true){
                              Cookie::delete('oauth_token');
                              Cookie::delete('oauth_token_secret');
                      }
                  }  catch (EpiTwitterNotAuthorizedException $e){
                    //catch twitter AuthorizedException
                  }
		if(Auth::instance()->logout()) {			
			return $this->request->redirect('');
		}
	}

	public function action_registrate()
	{
		if($this->request->method() != 'POST' && ! Helper_iAuth::instance()->isLoggedin()) {
			if(!Helper_iAuth::instance()->facebookAuth() && FacebookAuth::factory()->getMe()) {
				return $this->request->redirect('users/facebook_registration');
			}
		}		
		
		if(Helper_iAuth::instance()->isLoggedin() )	{
			if((isset($_POST['json']) && $_POST['json'] == 1)) {
				Helper_JsonResponse::addError(Helper_Output::getErrorCode('Permission Denied'));
				Helper_JsonResponse::addText('failure');
				Helper_JsonResponse::render();
				return;
			} else {
				$this->request->redirect('');
			}
		}

		if($this->request->method() != 'POST') {
			Helper_Output::factory()
                                     ->link_js('jquery.validate.min')
                                     ->link_js('frontend/registration');

			$data['prevalue'] = Helper_Output::flashData('prevData');
			$this->setTitle("User Registration")
				->view('user/registrate', $data)
				->render()
				;
		} else {

			$user = ORM::factory('user');
			try {
                            $data = $_POST;
                            if(isset($_POST['json'])) {
                                    $data['termofuse'] = 1;
                            }
                                
    			$user->create_user($data, array('firstname', 'lastname','password', 'email', 'termofuse', 'facebook_id'));
    			$user->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());

    			//fake autologin
    			$newUser = ORM::factory('user')->where('id', '=', $user->id)->find();
    			Session::instance()->regenerate();
    			Session::instance()->set('auth_user', $newUser);
    			Helper_iAuth::instance()->createSession();

    			if(isset($_POST['json']) && $_POST['json'] == 1) {
					Helper_JsonResponse::addData(array(
								'user_info'  => Helper_iAuth::instance()->getUserData(),
								'sess_token' => Helper_iAuth::$token
								));
					Helper_JsonResponse::addText('success');
					Helper_JsonResponse::render();
					return;
    			} else {
    				//go to login page after create user
    				$this->request->redirect('users/profile');
    			}
    			
                          } 
			catch (ORM_Validation_Exception $e) {
                            
    			Helper_Output::addErrors($e->errors(''));
    			Helper_Output::keepErrors();

    			if(isset($_POST['json']) && $_POST['json'] == 1) {
					foreach (Helper_Output::getErrors() as $key => $value) {
						if(is_array($value)) {
                                                      foreach ($value as $key1 => $value1) {
                                                              Helper_JsonResponse::addError( Helper_Output::getErrorCode($value1) );
                                                      }
						} else {
                                                      Helper_JsonResponse::addError(Helper_Output::getErrorCode($value));
						}
					}
					Helper_JsonResponse::addText('failure');
					Helper_JsonResponse::render();
					return;
				} else {
	    			Helper_Output::set_flashData('prevData', $_POST);
	    			$this->request->redirect('users/registrate');
                                }
			}

		}
	}
        
        public function action_ajax_check_email()
        {
            echo ORM::factory('user')->where('email', '=', $this->request->post('email'))->find()->id ? 0 : 1;
        }

}
