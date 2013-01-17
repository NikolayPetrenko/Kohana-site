<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Feeds extends My_ApiController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_index()
        {
            $pet_id  = $this->request->post('pet_id');
            $limit   = $this->request->post('limit');
            $offset  = $this->request->post('offset');
            
            $feeds   = Model_Feed::getMyFriendsFeeds($this->logget_user->id, $pet_id, $limit, $offset);
            foreach ($feeds as $feed)
            {
              $feed->date_created = strtotime($feed->date_created);
              if($feed->image)
                  $feed->image    = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$feed->pet_id.'/'.$feed->image;
            }
            
            Helper_JsonResponse::addData(array('feeds' => $feeds));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_details()
        {
            $feed_id  = $this->request->post('feed_id');
            $feed     = ORM::factory('feed', $feed_id);
            switch ($feed->code_name)
            {
                case ('upload_photo'): 
                  $pet_photo = ORM::factory('pet_photo')->where('pet_id', '=', $feed->pet_id)->where('date_created', '=', $feed->date_created)->find();
                  $details   = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2) . $pet_photo->pet_id . '/' . $pet_photo->name;
                  break;
                case ('check_in_location'):
                  $details   = ORM::factory('pet', $feed->pet_id)->locations->find()->build_location_for_request(false);
                  break;
                case ('find_pet'):
                  $id = ORM::factory('pet_find')->where('user_id', '=', $feed->user_id)->where('pet_id', '=', $feed->pet_id)->order_by('date_created', 'desc')->find()->id;
                  $details   = Helper_Pet::getAlertDetailsByType('find', $id);
                  break;
                case ('lost_pet'):
                  $details   = Helper_Pet::getAlertDetailsByType('lost', $feed->pet_id);
                  break;
                case ('change_pet_status'):
                  $details   = ORM::factory('pet', $feed->pet_id)->text_status;
                  break;
                default : $details= '';
            }
            Helper_JsonResponse::addData(array('details' => $details));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
                
        
        
        
        
}