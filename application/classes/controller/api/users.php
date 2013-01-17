<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Users extends My_ApiController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_updateProfile()
        {
            try{
                $waiting_filds_for_update =  array('firstname', 'lastname', 'state', 'city', 'zip', 'address', 'primary_phone', 'secondary_phone', 'avatar');
                $user = $this->logget_user;
                $user->update_user($this->request->post(), $waiting_filds_for_update);
            }  catch (ORM_Validation_Exception $e){
                Helper_JsonResponse::addError(Helper_Output::getErrorCode("Incorrect user data"));
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            }
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_setCoordinates()
        {
            $latitude  = $this->request->post('latitude');
            $longitude = $this->request->post('longitude');
            Model_User_Location::setCoordinates($this->logget_user->id, $latitude, $longitude);
            Helper_JsonResponse::addData(array('count' => Model_Pet_Lost::getCountInRadius($latitude, $longitude, Kohana::$config->load('config')->get('alerts.radius'))));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_setDeviceToken()
        {
            if($this->request->post('device_token'))
            {
              $this->logget_user->set('device_token', $this->request->post('device_token'))->update();
              Helper_JsonResponse::addText('success');
              Helper_JsonResponse::render();
            }
            
        }
        
        public function action_setFacebookToken()
        {
            $this->logget_user->facebook_token       = $this->request->post('facebook_token');
            $this->logget_user->facebook_expire_date = $this->request->post('facebook_expire_date');
            $this->logget_user->update();
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_setTwitterToken()
        {
            $this->logget_user->twitter_token = $this->request->post('twitter_token');
            $this->logget_user->twitter_secret = $this->request->post('twitter_secret');
            $this->logget_user->update();
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
}