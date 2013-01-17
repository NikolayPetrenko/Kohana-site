<?php defined('SYSPATH') or die('No direct script access.');

class Controller_About extends My_UserController {

    public function before() {
        parent::before();
        Helper_MainMenuHelper::setActiveItem('about');
    }
  
	public function action_index()
	{
		$this->setTitle("Home page")
			->view('about')
			->render()
			;
	}

}
