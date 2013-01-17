<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends My_AdminController {
          
        public function before() {
            parent::before();
            Helper_AdminSiteBar::setActiveItem('dashboard');
        }




        public function action_index()
	{
		$this->setTitle("Home page")
			->view('admin/dashboard/index')
			->render()
			;
	}

}
