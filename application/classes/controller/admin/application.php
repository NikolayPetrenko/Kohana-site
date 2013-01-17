<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Application extends My_AdminController {
        
        public function before() {
            parent::before();
            Helper_AdminSiteBar::setActiveItem('application');
        }


        public function action_index()
	{
          
                   $this->setTitle("Home page")
			->view('admin/application/index')
			->render()
			;
	}

}
