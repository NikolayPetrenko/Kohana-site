<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Info extends my_usercontroller
{
	public function before()
	{
		parent::before();
	}
        
        public function action_term_of_use()
        { 
            $data['term_of_use'] = ORM::factory('setting', array('key' => 'terms_of_use'))->value;
            $this->setTitle("Terms of use")
                 ->view("info/terms", $data)
                 ->render()
                 ;
        }

}
