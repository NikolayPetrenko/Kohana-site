<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Settings extends My_LoggetUserController 
{
	public function before()
	{
		parent::before();
                Helper_Tab::setActiveItem('myconfig');
	}
        
        public function action_index()
        { 
            $data['me'] = $this->logget_user;
            $data['facebook_user'] =  FacebookAuth::factory()->getMe(); 
            try{
              $data['twitter_user'] = Helper_Twitter::instance($this->logget_user->twitter_token, $this->logget_user->twitter_secret)->getAccount();
            }  catch (EpiTwitterNotAuthorizedException $e){
              $this->logget_user->set('twitter_secret', NULL)->set('twitter_token', NULL)->update();
              //$this->action_unlink_twitter();
            }
            
            $this->setTitle($data['me']->firstname. " Account Settings ")
                 ->view('settings/index', $data)
                 ->render()
                 ;
        }
        
        public function action_unlink_facebook()
        {
            $this->logget_user->set('facebook_token', NULL)->update();
            $this->request->redirect($this->request->referrer());
        }
        
        public function action_link_facebook()
        {
            if (Helper_iAuth::instance()->isFacebookLoggedIn())
            {  
                $token = FacebookAuth::factory()->getFacebook()->getAccessToken();
                $res = Helper_Facebook::instance()->regenerateFacebookToken($token);
                $this->logget_user->facebook_token       = $res['access_token'];
                $this->logget_user->facebook_expire_date = time() + $res['expires'];
                $this->logget_user->update();
                
                $this->request->redirect(URL::site('settings'));
            } else {
                $this->request->redirect(FacebookAuth::factory()->login_url());
            }
        }
        public function action_unlink_twitter()
        {
            $this->logget_user->set('twitter_secret', NULL)->set('twitter_token', NULL)->update();
            Cookie::delete('oauth_token');
            Cookie::delete('oauth_token_secret');
            $this->request->redirect($this->request->referrer());
        }
        
        public function action_link_twitter()
        {
            if(isset($_GET['oauth_token']) && $_GET['oauth_token'] != '') 
            {
                Helper_iAuth::getTwitter()->setToken($_GET['oauth_token']);
                $token = Helper_iAuth::getTwitter()->getAccessToken();  
                $this->logget_user->update_user(array(
                                                      'twitter_token' => $token->oauth_token,
                                                      'twitter_secret' => $token->oauth_token_secret
                                                      ),
                                               array('twitter_token', 'twitter_secret'));
                Cookie::set('oauth_token',$token->oauth_token,60*60*24*7);
                Cookie::set('oauth_token_secret',$token->oauth_token_secret,60*60*24*7);
                $this->request->redirect(URL::base() . 'users/profile/edit');
            } else {
                $this->request->redirect(Helper_iAuth::getTwitter()->getAuthenticateUrl());
            }
        }


}
