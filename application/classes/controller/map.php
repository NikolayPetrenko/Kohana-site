<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Map extends My_LoggetUserController {

      public function before() {
        parent::before();
        Helper_MainMenuHelper::setActiveItem('map');
        Helper_Tab::setActiveItem('map');
      }


        public function action_index()
	{
                Helper_Output::factory()->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                        ->link_js('frontend/map/index');
                $data['location_categories'] = ORM::factory('location_category')->find_all();
		$this->setTitle("MAP")
			->view('map/index', $data)
			->render()
			;
	}
        
        public function action_location()
        {
            $data['location']       = ORM::factory('location', $this->request->param('id'));
            $data['check_in_pets']  = $data['location']->pets->find_all();
            $this->setTitle($data['location']->name)
			->view('map/location', $data)
			->render()
			;
        }
        
        public function action_alerts()
        {
            Helper_MainMenuHelper::setActiveItem('alerts_map');
            Helper_Output::factory()->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                    ->link_js('frontend/map/alerts');
            $this->setTitle("Location Alerts")
			->view('map/alerts')
			->render()
			;
        }
        
}
