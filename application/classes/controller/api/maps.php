<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Maps extends My_ApiController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_getLocations()
        {
            $latitude  = $this->request->post('latitude');
            $longitude = $this->request->post('longitude');
            $category  = $this->request->post('category');
            
            if(!$latitude || !$longitude)
            {
                Helper_JsonResponse::addError(Helper_Output::getErrorCode("Your coordinates are not set"));
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            }
              
            $locations = Model_Location::getLocationsNearMe($latitude, $longitude, 80, $this->logget_user->id, $category);
            Helper_JsonResponse::addData(array('locations' => $locations));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
            
        }
        
        
        public function action_addLocations()
        {
            $_POST = Arr::map('HTML::chars', $this->request->post());
            $id = ORM::factory('location')->getPoint()->setPoint($_POST);
            $location = ORM::factory('location', $id[0]);
            if(!$location->has('confirms', $this->logget_user)){
                $location->add('confirms', $this->logget_user);
                $feed = $this->logget_user->firstname . ' ' . $this->logget_user->lastname . ' add new location: ' . $location->name;
                Helper_Feed::factory($this->logget_user->id, null)->setFeed($feed)->save();
            }
            Helper_JsonResponse::addData(array('id' => $location->id));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
            
        }
        
        public function action_setCheckin()
        {
            $location = ORM::factory('location', $this->request->post('location_id'));
            $petIDs   = array();
            $petIDs   = json_decode($this->request->post('petIDs'));
            
            foreach ($petIDs as $pet_id){
                $pet      = ORM::factory('pet', $pet_id);
                
                if($location->id){
                    $pet->remove('locations');
                    $location->add('pets', $pet);
                    ORM::factory('location_checkin')->setOwner($location->id, $pet->id, $pet->owner->id);
                    $feed = $pet->name . ' has Checked in at ' . $location->name;
                    Helper_Feed::factory($this->logget_user->id, $pet->id)->setCodeName('check_in_location')->clearOldThisFeeds()->setFeed($feed)->save();
                }
            }
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
            
        }
        
        public function action_unsetCheckin()
        {
            $location = ORM::factory('location', $this->request->post('location_id'));
            $petIDs   = array();
            $petIDs   = json_decode($this->request->post('petIDs'));
            
            foreach ($petIDs as $pet_id){
              $pet      = ORM::factory('pet', $pet_id);
              if($pet->owner->id == $this->logget_user->id){
                  $location->remove('pets', $pet);
                  Helper_Feed::factory($this->logget_user->id, $pet->id)->setCodeName('check_in_location')->clearOldThisFeeds();
              }
            }
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_setConfirm()
        {
            $location = ORM::factory('location', $this->request->post('location_id'));
            if($location->id){
                if(!$location->has('confirms', $this->logget_user)){
                    $location->add('confirms', $this->logget_user);
                    Helper_JsonResponse::addText('success');
                    Helper_JsonResponse::render();
                }
            }
        }
        
        public function action_getMapWithAlerts()
        {
            $pets = Model_Pet::alerts($this->logget_user->id, 
                                      $this->request->post('latitude'), 
                                      $this->request->post('longitude'),
                                      $this->request->post('status'),
                                      Kohana::$config->load('config')->get('alerts.radius'));
            
            Helper_JsonResponse::addData(array('pets' => $pets));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_getAlertDetails()
        {
            $response = Helper_Pet::getAlertDetailsByType($this->request->post('type'), $this->request->post('request_id'));
            Helper_JsonResponse::addData(array('response' => $response));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        
}