<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Settings extends My_AdminController {
        
        public function before() {
            parent::before();
        }


        public function action_index()
	{
            Helper_AdminSiteBar::setActiveItem('settings');
            if($this->request->post()){
                foreach ($this->request->post() as $key => $item){
                  $setting        = ORM::factory('setting', array('key' => $key));
                  $setting->value = $item;
                  $setting->update();
                }
                Helper_Alert::set_flash('Setting was update');
            }
            
            $data['settings'] = ORM::factory('setting')->find_all();
            $data['back']     = $this->request->referrer();
            Helper_Output::factory()//->link_css('editor/style')
                                    //->link_js('tinyeditor')
                                    ->link_js('admin/settings/index');
            $this->setTitle("Settings")
                ->view('admin/settings/index', $data)
                ->render()
                ;
	}
        
        public function action_tag_cost()
        {
            Helper_AdminSiteBar::setActiveItem('tag_cost');
            preg_match('/(-?\d+)(\d\d)/', ORM::factory('setting', array('key' => 'tag_cost'))->value, $matches);
            $data['dollars'] = $matches[1];
            $data['cents']   = $matches[2];
            
            
            if($this->request->post()){
              
              $valid = Validation::factory($_POST)->rule('dollars', 'not_empty')
                                                  ->rule('cents', 'not_empty')
                                                  ->rule('dollars', 'numeric')
                                                  ->rule('cents', 'numeric')
                                                  ->rule('dollars', 'min_length', array(':value', '1'))
                                                  ->rule('cents', 'min_length', array(':value', '2'));
              if($valid->check())
              {
                  ORM::factory('setting', array('key' => 'tag_cost'))->set('value', $this->request->post('dollars').$this->request->post('cents'))->update();
              }else{
                  Helper_Output::addErrors($valid->errors('pays'));
                  Helper_Output::keepErrors();
                  $this->request->redirect($this->request->referrer());
              }
              

              
              Helper_Alert::set_flash('Cost was update');
              $this->request->redirect($this->request->referrer());
            }
            
            Helper_Output::factory()->link_js('jquery.validate.min')
                                    ->link_js('admin/settings/tag_cost');
            $data['back']     = $this->request->referrer();
            $this->setTitle("Tag Cost")
                 ->view('admin/settings/tag_cost', $data)
                 ->render()
                 ;
        }

}
