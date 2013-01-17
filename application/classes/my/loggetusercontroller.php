<?php defined('SYSPATH') or die('No direct script access.');

class My_LoggetUserController extends My_UserController
{
	
	public function before()
	{
		parent::before();
                //check logget user
                if(empty($this->logget_user->id))
                {
                  $this->request->redirect('');
                }
                
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
                
                
                //Helper_Output::factory()->link_css('stylesheet');
                
	}
}
