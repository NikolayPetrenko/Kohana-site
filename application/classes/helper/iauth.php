<?php
class Helper_iAuth 
{
	protected $_table 	= 'sessions';
	protected $_liveTime	= 1209600;
	public static $token	= '';
	public static $twinstance = false;

	public $twitterObj = NULL;
	public $twitterInfo = NULL;
	
	public function __constructor()
	{
		$this->_table = 'sessions';
	}

	public static function instance()
	{
		return new Helper_iAuth();
	}

	public function createSession($user = false)
	{
		
		$this->gc();
		if(!$user) {
			$currentUser 	= Auth::instance()->get_user()->id;
			$userInfo	= serialize(Auth::instance()->get_user());
			self::$token 	= md5( md5($currentUser) . time() );
		} else {
			$currentUser 	= $user->id;
			$userInfo	= serialize($user);
			self::$token 	= md5( md5($currentUser) . time() );
		}

		$res = DB::insert($this->_table, array('session_id', 'last_active', 'contents'))->values(array(self::$token, time(), $userInfo))->execute();
	}

	public function gc()
	{
		$reanNumber = rand(0,10);

		//proportion of the probability ~10%
		//TODO reduce to 5%
		if($reanNumber == 5) {
			DB::delete($this->_table)->where('last_active', '<', time()-$this->_liveTime)->execute();
		}
	}

	public function facebookAuth()
	{
		$fb = FacebookAuth::factory();

		if(FacebookAuth::factory()->getMe()) {
                  
			$user = $fb->getMe();
                        $ourUser = ORM::factory('user')->where('email','=', $user['email'])->find();
                        
			if($ourUser->id) {
                                $ourUser->facebook_id 		= $user['id'];
				$ourUser->logins 		= $ourUser->logins+1;
				$ourUser->last_login            = time();
				try {
					$ourUser->update();
				}
				catch(ORM_Validation_Exception $e) {
					Helper_Main::print_flex($e->errors(''));
				}
				
				Session::instance()->regenerate();
    			Session::instance()->set('auth_user', $ourUser);
    			//generate token for user
				Helper_iAuth::instance()->createSession();
    			return true;
			} else {

				return false;
				// $password = Text::random('alnum');
				// $data  = array(
				// 	'firstname' 		=> $user['first_name'],
				// 	'lastname'			=> $user['last_name'],
				// 	'email'				=> $user['email'],
				// 	'password'			=> $password,
				// 	'password_confirm'	=> $password
				// );

				// try {
				// 	$ourUser->create_user($data, array('firstname', 'lastname','password', 'email'));
				// 	$ourUser->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());

				// 	$newUser = ORM::factory('user')->where('id', '=', $ourUser->id)->find();

				// 	//send message to user with generated password
				// 	Mailer::factory('user')->send_setpassword(array(
				// 													'user'	=> array(
				// 														"name" 		=> $user['first_name'],
				// 														'email'		=> $user['email'],
				// 														'password'	=> $password
				// 													)
				// 												));
				// 	Session::instance()->regenerate();
				// 	Session::instance()->set('auth_user', $newUser);
				// 	//generate token for user
				// 	Helper_iAuth::instance()->createSession();
				// 	return true;
	   //  		}
	   //  		catch (ORM_Validation_Exception $e) {
	   //  			Helper_Main::print_flex($e->errors(''));
	   //  		}
			}

			// FacebookAuth::factory()->getFacebook()->destroySession();
			// header('Location: ' . FacebookAuth::factory()->logout_url());
			// $this->request->redirect(FacebookAuth::factory()->logout_url());
			// var_dump(Text::random('alnum'));die;
			// Helper_Main::print_flex($user);
		}

		return false;
	}

	public function isTwitterLoggedin()
	{
		if(isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret'])) {
			$this->twitterAuth();
			return true;
		} else {
			$twitterObj = new EpiTwitter(Kohana::$config->load('social')->get('twitter.consumer.key'), 
										Kohana::$config->load('social')->get('twitter.consumer.secret'));
			return $twitterObj->getAuthorizationUrl();
		}
	}
        
        public function isFacebookLoggedIn()
        {
              $fb = FacebookAuth::factory();
		if($fb->getMe()){
                    return true;
                } else {
                    return false;
                }
        }

        public function twitterAuth()
	{
		$twitterObj = new EpiTwitter(Kohana::$config->load('social')->get('twitter.consumer.key'), 
                                             Kohana::$config->load('social')->get('twitter.consumer.secret'),
		Cookie::get('oauth_token'), Cookie::get('oauth_token_secret'));
                $twitterInfo = $twitterObj->get_accountVerify_credentials();
                
		$twitterInfo->response;  
		return $twitterInfo;
	}

	public function isLoggedin()
	{

		if(isset($_POST['sess_token']) && $_POST['sess_token'] != '') {
			$userSession = $this->getLoggedUser();
			if($userSession) {
				return true;
			} else {
				return false;
			}
			
		} else {
			return Auth::instance()->logged_in();
		}
	}

	public function getUserData()
	{
                $current_user = $this->getLoggedUser();
                $info = array();
                if($current_user){
                  $info['invites_count']  = $current_user->friends->where('accepted', '=', 0)->count_all();
                  $res  = $current_user->as_array();
                  if(!empty($res['avatar']))
                    $info['avatar'] = URL::base().substr(Kohana::$config->load('config')->get('user.avatars'), 2).$res['avatar'];
                  $info['id']                   = $res['id'];
                  $info['email']                = $res['email'];
                  $info['firstname']            = $res['firstname'];
                  $info['lastname']             = $res['lastname'];
                  $info['state']                = $res['state'];
                  $info['city']                 = $res['city'];
                  $info['address']              = $res['address'];
                  $info['zip']                  = $res['zip'];
                  $info['primary_phone']        = $res['primary_phone'];
                  $info['secondary_phone']      = $res['secondary_phone'];
                  $info['facebook_token']       = $res['facebook_token'];
                  $info['facebook_expire_date'] = $res['facebook_expire_date'];
                  $info['twitter_token']        = $res['twitter_token'];
                  $info['twitter_secret']       = $res['twitter_secret'];
                }
                
                return $info;
	}

	public function getLoggedUser()
	{
		if(isset($_POST['sess_token']) && $_POST['sess_token'] != '') {
			$userSession = DB::select()->from($this->_table)->where('session_id', '=', $_POST['sess_token'])->execute()->as_array();

			//increase live time
			DB::update($this->_table)->set(array('last_active'=> time()))->where('session_id', '=', $_POST['sess_token'])->execute();

			if(count($userSession) > 0) {
				$user = unserialize($userSession[0]['contents']);
				return $user;
			} else {
				return false;
			}
		} else {
                        try{
                          return Auth::instance()->get_user();
                        }  catch (Session_Exception $e){
                          
                        }
                        
		}
	}
	
	public static function getTwitter()
	{
		$token	= Kohana::$config->load('social')->get('twitter.consumer.key');
		$secret	= Kohana::$config->load('social')->get('twitter.consumer.secret');
		
		if(!self::$twinstance) {
			self::$twinstance = new EpiTwitter($token, $secret);
			
		}
		return self::$twinstance;
	}
}

