<?php defined('SYSPATH') or die('No direct script access.');

class My_UserController extends MY_Layout
{
	
	public function before()
	{
		parent::before();
		$this->template = View::factory('layouts/main');
                $this->logget_user = Helper_iAuth::instance()->getLoggedUser();
		//TODO remove cookie later need to remove
		// Cookie::delete('fbsr_'.FacebookAuth::factory()->getFacebook()->getAppId());
		// Cookie::delete('session_name');
                Helper_MainMenuHelper::init(Kohana::$config->load('config')->get('main_menu'));
                Helper_Tab::init(Kohana::$config->load('config')->get('account_tabs'));
                
                if(!empty($this->logget_user->id)){
                  Helper_MainMenuHelper::setLogged(TRUE);
                  //check facebook conection
                  if(empty($this->logget_user->facebook_token))
                  {
                    Helper_Tab::disableItem('facebook');
                  }

                  //check twitter conection
                  if(empty($this->logget_user->twitter_token) || empty($this->logget_user->twitter_secret))
                  {
                    Helper_Tab::disableItem('twitter');
                  }
                  $user = Helper_iAuth::instance()->getUserData();
                  if($user['invites_count'])
                    Helper_Tab::setPropertyForTab('unleashed', 'invites_count', $user['invites_count']);
                }
                
                Helper_Output::factory()->link_css('main')
                                        ->link_js('layouts/main');
	}
}
