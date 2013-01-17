<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Messages extends My_LoggetUserController 
{
	public function before()
	{
            parent::before();
            Helper_Output::factory()->link_js('jquery.validate.min');
            Helper_MainMenuHelper::setActiveItem('messages');
	}

	public function action_index()
        {
            $data['threads'] = Model_User_Message::getAllTreads($this->logget_user->id);
            Helper_Output::factory()->link_js('frontend/messages/index');
            $this->setTitle("My Messages")
                  ->view('messages/index', $data)
                  ->render()
                  ;
        }
        
        public function action_dialog()
        {
            $data['companion'] = ORM::factory('user', $this->request->param('id'));
            $data['me']        = $this->logget_user;
            $data['dialog']    = $this->logget_user->messages
                                                  ->where('user_id', '=', $data['companion']->id)
                                                  ->or_where('addressee_id','=', $data['companion']->id)
                                                  ->order_by('date_create', 'asc')->find_all();
            if($this->request->post()){
                $_POST = Arr::map('HTML::chars', $this->request->post());
                try{
                ORM::factory('user_message')->save_message($_POST, array('user_id', 'addressee_id', 'message'));
                }  catch (ORM_Validation_Exception $e){
                  Helper_Output::addErrors($e->errors(''));
                  Helper_Output::keepErrors();
                }
                $this->request->redirect($this->request->referrer());
            }

            Helper_Output::factory()->link_js('frontend/messages/dialog');
            $this->setTitle("My Messages")
                  ->view('messages/dialog', $data)
                  ->render()
                  ;
        }
        
        
       public function action_removeItem()
       {
          ORM::factory('user_message', $this->request->post('id'))->delete();
          Helper_JsonResponse::addText('success');
          Helper_JsonResponse::render();
       }
}