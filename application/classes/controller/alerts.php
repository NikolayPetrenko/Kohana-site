<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Alerts extends My_LoggetUserController {

        public function before() {
            parent::before();
            Helper_MainMenuHelper::setActiveItem('alerts');
        }

        public function action_index() {       
          
            $data['lost_alerts']          = Model_Pet::web_alerts($this->logget_user->id, 'lost', Kohana::$config->load('config')->get('lists.count'), 0);
            $data['find_alerts']          = Model_Pet::web_alerts($this->logget_user->id, 'find', Kohana::$config->load('config')->get('lists.count'), 0);
            $data['unknown_alerts']       = Model_Pet::web_alerts($this->logget_user->id, 'unknown', Kohana::$config->load('config')->get('lists.count'), 0);
            
            $data['lost_alerts_count']    = Model_Pet::web_alerts($this->logget_user->id, 'lost', false, false, true);
            $data['find_alerts_count']    = Model_Pet::web_alerts($this->logget_user->id, 'find', false, false, true);
            $data['unknown_alerts_count'] = Model_Pet::web_alerts($this->logget_user->id, 'unknown', false, false, true);
            
            Helper_Output::factory()->link_js('frontend/alerts/index');
            $this->setTitle("Alerts")
                    ->view('alerts/index', $data)
                    ->render()
                    ;
	}
        
        public function action_getMoreAlerts()
        {
            $alerts       = Model_Pet::web_alerts($this->logget_user->id, $this->request->post('status'), Kohana::$config->load('config')->get('lists.count'), $this->request->post('offset'));
            $alerts_count = Model_Pet::web_alerts($this->logget_user->id, $this->request->post('status'), false, false, true);
            $flag = false;
            if(Kohana::$config->load('config')->get('lists.count') + $this->request->post('offset') >= $alerts_count)
              $flag = true;
            
            Helper_JsonResponse::addData(array('html' => View::factory('alerts/partial/index')->set('alerts', $alerts)->render(),
                                               'flag' => $flag));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_details() {
            
            $data['type']    = $this->request->param('type');
            $data['details'] = Helper_Pet::getAlertDetailsByType($this->request->param('type'), $this->request->param('id'));
            
            Helper_Output::factory()->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                    ->link_js('frontend/alerts/details');
            $this->setTitle($data['type']." Alerts")
			->view('alerts/details', $data)
			->render()
			;
        }
        
}
