<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends My_AdminController {
        
        public function before() {
            parent::before();
            Helper_AdminSiteBar::setActiveItem('users');
        }


        public function action_index()
	{
          
	}
        
        public function action_list()
        { 
            Helper_Output::factory()
                          ->link_js('jquery.dataTables.min')
                          ->link_js('jquery.dataTables.pagination')
                          ->link_js('admin/users/list');
            $this->setTitle("Home page")
                  ->view('admin/users/list')
                  ->render()
                  ;
        }
        
        
        public function action_getAjaxData()
        {
            
            $offset      = $this->request->query('iDisplayStart');
            $limit       = $this->request->query('iDisplayLength');
            $columns     =   array();
            $columns[]   =   'id';
            $columns[]   =   'email';
            $columns[]   =   'firstname';
            $columns[]   =   'lastname';
            $columns[]   =   'id';
            $columns[]   =   'primary_phone';
            $columns[]   =   'logins';
            $columns[]   =   'dob';
            $columns[]   =   'last_login';
            $columns[]   =   'id';
            
            
            $users = ORM::factory('user');
            if($this->request->query('sSearch')) {
                $users->where('email', 'like',  trim($this->request->query('sSearch')). '%');
            }
            
            $users = $users->limit($limit)->offset($offset)->order_by($columns[$this->request->query('iSortCol_0')], $this->request->query('sSortDir_0'))->find_all();
            
            $data['iTotalDisplayRecords'] =  ORM::factory('user')->count_all();
            $data['iTotalRecords']        =  $data['iTotalDisplayRecords'];
                               
            if(count($users)) {
		foreach ($users as $key => $user){
                    $tempArray    =   array();
                    $tempArray[]  =   $user->id;
                    $tempArray[]  =   $user->email;
                    $tempArray[]  =   $user->firstname;
                    $tempArray[]  =   $user->lastname;
                    $tempArray[]  =   '<span class="label">' . $user->roles->order_by('role_id', 'desc')->find()->name . '</span>';
                    $tempArray[]  =   $user->primary_phone ? $user->primary_phone : '---/---/----';
                    $tempArray[]  =   $user->logins;
                    $tempArray[]  =   Helper_Output::siteDateForOldDates($user->dob);
                    $tempArray[]  =   date(Kohana::$config->load('config')->get('date.format'), $user->last_login);
                    $tempArray[]  =   '<a class="btn btn-mini" href="'.URL::site('/admin/users/edit/'.$user->id).'"><i class="icon-pencil"></i>Edit</a>
                                       <a class="btn btn-mini btn-danger" onclick="javascript:list.removeItem('.$user->id.', this); return false;" href="#"><i class="icon-trash icon-white"></i>Remove</a>';
                    
                    $data['aaData'][] = $tempArray;
                }
            }else{
               $data['aaData'] = array(); 
            }
            
            echo json_encode($data);
        }


        public function action_edit()
        {
            if($this->request->post()){
                try {
                
                    $user = ORM::factory('User');
                    if($this->request->post('id')) {
                        $user = $user->where('id','=', $this->request->post('id'))->find();
                    }

                    $user->email           = $this->request->post('email');
                    $user->firstname       = $this->request->post('firstname');
                    $user->lastname        = $this->request->post('lastname');
                    $user->state           = $this->request->post('state');
                    $user->city            = $this->request->post('city');
                    $user->address         = $this->request->post('address');
                    $user->zip             = $this->request->post('zip');
                    $user->primary_phone   = $this->request->post('primary_phone');
                    $user->secondary_phone = $this->request->post('secondary_phone');
                    $user->dob             = Helper_Input::changeDateFormat($this->request->post('dob'));
                    
                    
                    $role = ORM::factory('role', $this->request->post('role_id'));
                    
                    if($role->id == 1 && $this->logget_user->id == $this->request->post('id'))
                    {
                        Helper_Alert::setStatus('error');
                        Helper_Alert::set_flash('You can not set this role for themselves');
                        $this->request->redirect(URL::site('admin/users/edit/'.$this->request->post('id')));
                    }
                    
                    $user->remove('roles');
                    $roles = 1;
                    while ($roles <= $role->id){
                        $roless[] = $roles;
                        $roles++;
                    }
                    $user->add('roles', $roless);
                      
                    
                    
                    if($this->request->post('avatar')){
                        $user->avatar        = $this->request->post('avatar');
                        @copy(Kohana::$config->load('config')->get('temp.upload').$this->request->post('avatar'), Kohana::$config->load('config')->get('user.avatars').$this->request->post('avatar'));
                        @unlink(Kohana::$config->load('config')->get('temp.upload').$this->request->post('avatar'));
                    }
                    
                    if($this->request->post('id')){
                        $user->update();
                        Helper_Alert::set_flash('Update info');
                        $this->request->redirect(URL::site('admin/users/edit/'.$this->request->post('id')));
                    }else{
                        $user->create();
                        Helper_Alert::set_flash('User info was save');
                        $this->request->redirect(URL::site('admin/users/list'));
                    }
                
                }
                catch(ORM_Validation_Exception $e) {
                        Helper_Output::addErrors($e->errors(''));
                }
            }
            
                $data['roles']     = ORM::factory('role')->find_all();
                $data['user']      = ORM::factory('user', $this->request->param('id'));
                Helper_Output::factory()->link_js('bootstrap-datepicker')
                                        ->link_js('jquery.validate.min')
                                        ->link_css('datepicker')
                                        ->link_js('jquery.ui.widget')
                                        ->link_js('jquery.fileupload')
                                        ->link_js('admin/users/edit');
                $this->setTitle("Edit user")
                    ->view('admin/users/edit', $data)
                    ->render()
                    ;
        }
        
        public function action_ajax_remove()
        {
            $user = ORM::factory('user', $this->request->post('id'));
            
            if($user->id == $this->logget_user->id){
                Helper_JsonResponse::addError('You can\'t remove yourself');
                Helper_JsonResponse::addText('failure');
                Helper_JsonResponse::render();
            }
            
            @unlink(Kohana::$config->load('config')->get('user.avatars').$user->avatar);
            $user->delete();
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
            
        }
        
        public function action_ajax_check_email()
        {
            echo ORM::factory('user')->where('email', '=', $this->request->post('email'))->where('id', '!=', $this->request->post('id'))->find()->id ? 0 : 1;
        }

}
