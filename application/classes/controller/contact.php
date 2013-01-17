<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends My_UserController {
  
  
      public function before() {
        parent::before();
        Helper_MainMenuHelper::setActiveItem('contact');
      }




      public function action_index()
	{
		$this->setTitle("Home page")
			->view('contact')
			->render()
			;
	}


}
