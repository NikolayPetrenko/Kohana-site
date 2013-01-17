<?php defined('SYSPATH') or die('No direct script access.');
class Mailer_User extends Mailer
{
	public function before()
	{
		$this->config = Kohana::$environment;
	}

	public function welcome($args) 
	{
		$this->to 		= array('anton@lodoss.org' => 'Anton');
		$this->from 		= array('theteam@theweapp.com' => 'The Team');
		$this->subject		= 'Welcome!';
		$this->data 		= $args;
	}

	public function setpassword($args)
	{
		$this->type 		= 'html';
		$this->to 		= array($args['user']['email'] => $args['user']['name']);
		$this->from 		= array('no-response@unleashed.com' => 'Unleashed Team');
		$this->subject		= 'Info!';
		$this->data 		= $args;	
	}

	public function forgotpasswordfirst($args) 
	{
		$this->type 		= 'html';
		$this->to 		= array($args['user']['email'] => $args['user']['name']);
		$this->from 		= array('no-response@unleashed.com' => 'Unleashed Team');
		$this->subject		= 'Reset Password Interview';
		$this->data 		= $args;
	}

	public function forgotpasswordsecond($args) 
	{
		$this->type 		= 'html';
		$this->to 		= array($args['user']['email'] => $args['user']['name']);
		$this->from 		= array('no-response@unleashed.com' => 'Unleashed Team');
		$this->subject		= 'Rescovery Password!';
		$this->data 		= $args;
	}
        
        public function petfind($args) 
	{
		$this->type 		= 'html';
		$this->to 		= array($args['user']['email'] => $args['user']['name']);
		$this->from 		= array('no-response@unleashed.com' => 'Unleashed Team');
		$this->subject		= 'Your pet was found!';
		$this->data 		= $args;
	} 
}