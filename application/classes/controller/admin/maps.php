<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Maps extends My_AdminController {
        
        public function before() {
            parent::before();
            Helper_AdminSiteBar::setActiveItem('maps');
        }
        
        public function action_list()
	{
            Helper_Output::factory()->link_js('jquery.dataTables.min')
                                    ->link_js('jquery.dataTables.pagination')
                                    ->link_js('admin/maps/list');
            
            $this->setTitle("Locations list")
                ->view('admin/maps/list')
                ->render()
                ;
	}
        
        public function action_getAjaxTable()
        {
            
            $offset      = $this->request->query('iDisplayStart');
            $limit       = $this->request->query('iDisplayLength');
            $columns     =   array();
            $columns[]   =   'id';
            $columns[]   =   'name';
            $columns[]   =   'category_id';
            $columns[]   =   'phone';
            $columns[]   =   'status';
            $columns[]   =   'isConfirm';
            $columns[]   =   'id';
            $columns[]   =   'id';
            
            $locations = ORM::factory('location');
            if($this->request->query('sSearch')) {
                $locations->where('name', 'like',  trim($this->request->query('sSearch')). '%');
            }
            
            $locations = $locations->limit($limit)->offset($offset)->order_by($columns[$this->request->query('iSortCol_0')], $this->request->query('sSortDir_0'))->find_all();
            
            $data['iTotalDisplayRecords'] =  ORM::factory('location')->count_all();//count($locations);
            $data['iTotalRecords']        =  $data['iTotalDisplayRecords'];
                               
            if(count($locations)) {
		foreach ($locations as $key => $location){
                    $tempArray    =   array();
                    $tempArray[]  =   $location->id;
                    $tempArray[]  =   $location->name;
                    $tempArray[]  =   $location->category->name;
                    $tempArray[]  =   $location->phone ? $location->phone : '---/---/----';
                    
                    //check access startus
                    if($location->status == 0)
                        $tempArray[]  = '<a class="btn btn-mini" onclick="javascript:list.changeStatus('.$location->id.', this); return false;" href="#"> Not Active</a>';
                    else
                        $tempArray[]  = '<a class="btn btn-mini btn-success" onclick="javascript:list.changeStatus('.$location->id.', this); return false;" href="#"> Active</a>';
                    //check admin confirm status
                    if($location->isConfirm == 0)
                        $tempArray[]  = '<a class="btn btn-mini" onclick="javascript:list.changeConfirmStatus('.$location->id.', this); return false;" href="#"> Not Confirm</a>';
                    else
                        $tempArray[]  = '<a class="btn btn-mini btn-success" onclick="javascript:list.changeConfirmStatus('.$location->id.', this); return false;" href="#"> Confirm</a>';
                    
                    $tempArray[]  =   $location->confirms->count_all();
                    $tempArray[]  =   '<a class="btn btn-mini" href="'.URL::site('/admin/maps/add_location/'.$location->id).'"><i class="icon-pencil"></i>Edit</a>
                                       <a class="btn btn-mini btn-danger" onclick="javascript:list.removeItem('.$location->id.', this); return false;" href="#"><i class="icon-trash icon-white"></i>Remove</a>';
                    
                    $data['aaData'][] = $tempArray;
                }
            }else{
               $data['aaData'] = array(); 
            }
            
            echo json_encode($data);
        }
        
        
        public function action_removeItem()
        { 
            $location = ORM::factory('location', $this->request->post('id'));
            @unlink(Kohana::$config->load('config')->get('location.pictures').$location->picture);
            $location->delete();
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }

        public function action_add_location()
        {
            $location = ORM::factory('location')->getPoint( $this->request->param('id'));
            if($this->request->post()) {
              $_POST['phone'] = Helper_Input::buildUSAPhoneInQueryForDBInsert($this->request->post('phone'));
              $_POST = Arr::map('HTML::chars', $_POST);
              $valid = Validation::factory($_POST)->rule('status', 'not_empty')
                                                  ->rule('name', 'not_empty')
                                                  ->rule('name', 'min_length', array(':value', '4'))
                                                  ->rule('category_id', 'not_empty')
                                                  ->rule('address', 'not_empty')
                                                  ->rule('phone', 'phone')
                                                  ->rule('latitude', 'numeric')
                                                  ->rule('longitude', 'numeric');
              
              if($valid->check()){
                  if($this->request->post('id')){
                      $location->setPoint($_POST, 'update');
                      Helper_Alert::set_flash('Location info was update');
                      $this->request->redirect('admin/maps/add_location/'.$this->request->post('id'));
                  }else{
                      $location->setPoint($_POST);
                      Helper_Alert::set_flash('Location was saved');
                      $this->request->redirect(URL::site('admin/maps/list'));
                  }
              }else{
                Helper_Output::addErrors($valid->errors('locations'));
                Helper_Output::keepErrors();
                $this->request->redirect($this->request->referrer());
              }
              
              
            } else {
                $data['location']       = $location;
                $data['categories']     = ORM::factory('location_category')->find_all();
                Helper_Output::factory()->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                        ->link_js('jquery.validate.min')
                                        ->link_js('jquery.ui.widget')
                                        ->link_js('jquery.fileupload')
                                        ->link_js('admin/maps/add_location')
                                        ;

                $this->setTitle("Add Location")
                      ->view('admin/maps/add_location', $data)
                      ->render()
                      ;
            }
        }
        
        public function action_changeLocationStatus()
        {
            $location = ORM::factory('location', $this->request->post('id'));
            if($location->status){
                $location->status = 0;
                $message = array('status' => 0);
            }else{
                $location->status = 1;
                $message = array('status' => 1);
            }
                $location->save();
            Helper_JsonResponse::addData($message);
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_changeLocationConfirm()
        {
            $location = ORM::factory('location', $this->request->post('id'));
            if($location->isConfirm){
                $location->isConfirm = 0;
                $message = array('status' => 0);
            }else{
                $location->isConfirm = 1;
                $message = array('status' => 1);
            }
                $location->save();
            Helper_JsonResponse::addData($message);
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
}
