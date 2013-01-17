<?php defined('SYSPATH') or die('No direct script access.');

class My_AdminController extends MY_Layout
{
        public function before()
	{
		parent::before();
                
                $this->logget_user = Helper_iAuth::instance()->getLoggedUser();
                
//                Helper_Output::factory()->link_css('bootstrap')
//					->link_js('bootstrap.min')
//					;
                
                
                if(empty($this->logget_user) || Helper_iAuth::instance()->getLoggedUser()->roles->where('role_id', '=', 2)->find()->name != 'admin')
                    $this->request->redirect ('');
                
                Helper_AdminSiteBar::init(Kohana::$config->load('admin_menu')->get('admin_sitebar'));
                
		$this->template = View::factory('layouts/admin');
                Helper_Output::factory()->link_css('admin')
                                        ->link_js('layouts/admin');;
	}
  
}