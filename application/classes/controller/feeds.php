<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feeds extends My_LoggetUserController 
{
	public function before()
	{
		parent::before();
		Helper_Output::factory()->link_js('jquery.validate.min');
                Helper_Tab::setActiveItem('feeds');
                Helper_MainMenuHelper::setActiveItem('feeds');
	}

	public function action_index()
        {
          $data['feeds']             = Model_Feed::getMyFriendsFeeds($this->logget_user->id, false, Kohana::$config->load('config')->get('lists.count'));
          $data['feeds_count']       = Model_Feed::getMyFriendsFeeds($this->logget_user->id, false, false, false, true);
          $data['pets']              = $this->logget_user->pets->find_all();
          $data['suggested_friends'] = Model_Pet::getSuggestedFriends($this->logget_user->id);
          Helper_Output::factory()->link_js('frontend/feeds/index');
          $this->setTitle("Feeds")
                 ->view('feeds/index', $data)
                 ->render()
                 ;
        }
        
        public function action_ajax_get_more_feeds()
        {
            $feeds = Model_Feed::getMyFriendsFeeds($this->logget_user->id, false, Kohana::$config->load('config')->get('lists.count'), $this->request->post('offset'));
            
            $flag  = false;
            if( $this->request->post('offset')+Kohana::$config->load('config')->get('lists.count') >= Model_Feed::getMyFriendsFeeds($this->logget_user->id, false, false, false, true))
              $flag = true;
            
            Helper_JsonResponse::addData(array('html' => View::factory('feeds/partial/feeds')->set('feeds', $feeds)->render(),
                                               'flag' => $flag));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_details()
        {
            $feed     = ORM::factory('feed', $this->request->param('id'));
            if($feed->id){
                switch ($feed->code_name)
                {
                    case ('add_pet'):
                      $this->request->redirect(URL::site($feed->pet_id));
                      break;
                    
                    case ('find_pet'):
                      $find_id = ORM::factory('pet_find')->where('user_id', '=', $feed->user_id)->where('pet_id', '=', $feed->pet_id)->find()->id;
                      $this->request->redirect(URL::site('alerts/details/find/'.$find_id));
                      break;
                    
                    case ('lost_pet'):
                      $this->request->redirect(URL::site($feed->pet_id));
                      break;
                    
                    case ('change_pet_status'):
                      $this->request->redirect(URL::site($feed->pet_id));
                      break;
                    
                    case ('upload_photo'): 
                      $this->request->redirect(URL::site('pets/gallery/'.$feed->pet_id));
                      break;
                    
                    case ('check_in_location'):
                      $location_id = ORM::factory('pet', $feed->pet_id)->locations->find()->id;
                      $this->request->redirect(URL::site('map/location/'.$location_id));
                      break;
                    default : $this->request->redirect($this->request->referrer());
                }
            }else{
              $this->request->redirect($this->request->referrer());
            }
            
          
          
        }
}