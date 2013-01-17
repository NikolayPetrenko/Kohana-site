<?php defined('SYSPATH') or die('No direct script access.');
//!! not protected controller
class Controller_Api_Settings extends Controller 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_getTagsInfo()
        {
            $video           = ORM::factory('setting', array('key' => 'tags_video'))->value;
            $tags_info       = ORM::factory('setting', array('key' => 'order_tags_info'))->value;
            $tags_info       = ORM::factory('setting', array('key' => 'tag_cost'))->value;
            Helper_JsonResponse::addData(array('video' => $video, 'tags_info' => $tags_info, 'tag_cost' => $tags_info));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_getTermOfUse()
        {
            echo ORM::factory('setting', array('key' => 'terms_of_use'))->value;
        }
        
        
}