<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pets extends My_LoggetUserController 
{
	public function before()
	{
		parent::before();
		Helper_Output::factory()->link_js('jquery.validate.min');
                Helper_Tab::setActiveItem('mypets');
	}

	public function action_list()
	{
          
            Helper_Output::factory()->link_js('jquery.dataTables.min')
                                    ->link_js('jquery.dataTables.pagination')
                                    ->link_js('frontend/pets/list');
            $this->setTitle("My Pets")
                 ->view('pets/list')
                 ->render()
                 ;
	}
        
        public function action_getAjaxDataTable()
        {
          
            $offset      = $this->request->query('iDisplayStart');
            $limit       = $this->request->query('iDisplayLength');
            $columns     =   array();
            $columns[]   =   'id';
            $columns[]   =   'name';
            $columns[]   =   'type_id';
            $columns[]   =   'breed_id';
            $columns[]   =   'dob';
            $columns[]   =   'id';
            $columns[]   =   'id';
            $columns[]   =   'id';
            
            $pets = $this->logget_user->pets;
            if($this->request->query('sSearch')) {
                $pets->where('name', 'like', mysql_real_escape_string(trim($this->request->query('sSearch')). '%'));
            }
            
            $pets = $pets->limit($limit)->offset($offset)->order_by($columns[$this->request->query('iSortCol_0')], $this->request->query('sSortDir_0'))->find_all();
            
            $data['iTotalDisplayRecords'] =  $this->logget_user->pets->count_all();
            $data['iTotalRecords']        =  $data['iTotalDisplayRecords'];
                               
            if(count($pets)) {
		foreach ($pets as $key => $pet){
                    $tempArray     =   array();
                    $tempArray[]   =   $pet->id;
                    $tempArray[]   =   '<a href="'. URL::site($pet->id) .'">'.$pet->name.'</a>';
                    $tempArray[]   =   $pet->type->type;
                    $tempArray[]   =   $pet->breed->breed;
                    $tempArray[]   =   Helper_Output::siteDateForOldDates($pet->dob);
                    
                    //check qrcode
                    if($pet->tag->qrcode)
                      $tempArray[] = '<i class="icon-ok-circle"></i>';
                    else
                      $tempArray[] = '<i class="icon-ban-circle"></i>';
                    //check lost pet data
                    if($pet->lost->pet_id)
                      $tempArray[] = '<i class="icon-ok-circle"></i>';
                    else
                      $tempArray[] = '<i class="icon-ban-circle"></i>';
                    
                    $tempArray[]   = '<a class="btn btn-mini" href="' . URL::base().'pets/gallery/' . $pet->id . '">  <i class="icon-camera"></i> Gallery</a>
                                      <a class="btn btn-mini " href="' . URL::base().'pets/edit/' . $pet->id . '">  <i class="icon-pencil"> </i> Edit</a>
                                      <a class="btn btn-mini btn-danger" href="javascript:void(0)" onclick="javascript: list.removeItem(' . $pet->id . ', this)"><i class="icon-trash icon-white"></i>Delete</a>';
                    $data['aaData'][] = $tempArray;
                }
            }else{
               $data['aaData'] = array(); 
            }
            
            echo json_encode($data);
        }
        
        public function action_gallery()
        {
            $id  = $this->request->param('id');
            $pet = ORM::factory('pet', $id);
            $data['photos'] = $pet->photos->find_all();
            Helper_Output::factory()
                                  ->link_js('jquery.ui.widget')
                                  ->link_js('jquery.fileupload')
                                  ->link_js('frontend/pets/gallery');
            $this->setTitle($pet->name . " Gallery")
                 ->view('pets/gallery', $data)
                 ->render()
                 ;
        }

        public function action_ajax_check_breed()
        {
            echo ORM::factory('breed')->where('breed', '=', $this->request->post('breed'))->find()->id ? 1: 0;
        }
        
        public function action_edit()
        {
            if($this->request->post()){
                  $_POST = Arr::map('HTML::chars', $_POST);
                  try {
                      
                        $pet = ORM::factory('pet');
                        if($this->request->post('id')) {
                            $pet = $pet->where('id','=', $this->request->post('id'))->find();
                            if($pet->owner->id != $this->logget_user->id)
                                $this->request->redirect ('pets/list');
                        }
                        
                        $pet->name         = $this->request->post('name');
                        $pet->type_id      = $this->request->post('type');
                        $pet->description  = $this->request->post('description');
                        $pet->dob          = Helper_Input::changeDateFormat($this->request->post('dob'));
                        $pet->picture      = $this->request->post('picture');
                        
                        if($this->request->post('picture')){
                            if(!is_dir(Kohana::$config->load('config')->get('pets.pictures').$pet->id))
                              @mkdir(Kohana::$config->load('config')->get('pets.pictures').$pet->id, 0777, TRUE);
                            @copy(Kohana::$config->load('config')->get('temp.upload').$this->request->post('picture'), Kohana::$config->load('config')->get('pets.pictures').$pet->id.'/'.$this->request->post('picture'));
                            @unlink(Kohana::$config->load('config')->get('temp.upload').$this->request->post('picture'));
                        }
                        
                        $pet->user_id      = $this->logget_user->id;
                        $breed = ORM::factory('breed')->where('breed', '=', trim($this->request->post('breed')))->where('type_id', '=', $this->request->post('type'))->find();
                        if($breed->id)
                            $pet->breed_id = $breed->id;
                        
                        
                        
                        if($this->request->post('id')){
                            $pet->update();
                            Helper_Pet::tryCreateFolder($pet->id);
                            if($this->request->post('picture'))
                            {
                                Helper_Pet::move_pet_file($pet->id, $this->request->post('picture'));
                            }
                            
                            if($this->request->post('isLost'))
                            {
                                $pet->lost->setDetails($this->request->post('lost'), $pet->id, $this->logget_user);
                            }
                            else
                            {
                                $pet->lost->clearDetails($pet->id);
                            }
                            
                            Helper_Alert::set_flash('Pet info was update');
                            $this->request->redirect(URL::site('pets/edit/'.$this->request->post('id')));
                        }else{
                            $pet->create();
                            Helper_Pet::tryCreateFolder($pet->id);
                            if($this->request->post('picture'))
                            {
                                Helper_Pet::move_pet_file($pet->id, $this->request->post('picture'));
                            }
                            
                            if($this->request->post('isLost'))
                            {
                                $pet->lost->setDetails($this->request->post('lost'), $pet->id, $this->logget_user);
                            }
                            else
                            {
                                $pet->lost->clearDetails($pet->id);
                            }
                            //add feed
                            $pet  = ORM::factory('pet', $pet->id);
                            $feed = 'Your friend '.$this->logget_user->firstname .' '. $this->logget_user->lastname.' add new '.$pet->type->type.' by name '.$pet->name;
                            Helper_Feed::factory($this->logget_user->id, $pet->id)->setCodeName('add_pet')->setFeed($feed)->save();

                            Helper_Alert::set_flash('Pet info was save');
                            $this->request->redirect(URL::site('pets/list'));
                        }
                
                    }
                    catch(ORM_Validation_Exception $e) {
                            Helper_Output::addErrors($e->errors(''));
                            Helper_Output::keepErrors();
                            $this->request->redirect($this->request->referrer());
                    }
  
            }
            
            
            $data['pet']      = ORM::factory('pet', $this->request->param('id'));
            $data['me']       = $this->logget_user;
            //TODO please check this on here is error
            if($data['pet']->id && $data['pet']->owner->id != $this->logget_user->id)
              $this->request->redirect ('pets/list');
            $data['types']  = ORM::factory('pet_type')->find_all();
            Helper_Output::factory()
                                    ->link_js('http://maps.google.com/maps/api/js?sensor=true&libraries=places&language=eng.js')
                                    ->link_js('bootstrap-datepicker')
                                    ->link_js('jquery.validate.min')
                                    ->link_js('jquery.ui.widget')
                                    ->link_js('jquery.fileupload')
				    ->link_css('datepicker')
                                    ->link_js('jquery.autocomplete')
                                    ->link_js('frontend/pets/edit');
            $this->setTitle("Edit Pet")
                 ->view('pets/edit', $data)
                 ->render()
                 ;
        }
        
        public function action_ajax_get_lost_container()
        {
            $pet = ORM::factory('pet', $this->request->post('pet_id'));
            Helper_JsonResponse::addData(array('html' => View::factory('pets/partial/lost_form')->set('pet', $pet)->set('me', $this->logget_user)->render()));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }


        public function action_ajax_get_breeds()
	{       
                $search = $this->request->query('term');
                $type   = $this->request->query('type');
                $breeds = array();
		foreach (ORM::factory('breed')->where('type_id', '=', $type)->where('breed', 'like', '%'.$search.'%')->find_all() as $key => $item)
		{   
                    $breeds[$key]         = $item->as_array();
                    $breeds[$key]['html'] = '<div>
                                              <span>' . Helper_Input::hightLight($search, $breeds[$key]['breed']) . '</span>
                                            </div>';
		}
		Helper_JsonResponse::addData(array('result' => $breeds));
		Helper_JsonResponse::addText('success');
		Helper_JsonResponse::render();
	}
        
        public function action_ajax_remove(){
            $pet = ORM::factory('pet', $this->request->post('id'));
            @rmdir(Kohana::$config->load('config')->get('pets.pictures').$pet->id);
            
            if($pet->delete()){
		Helper_JsonResponse::addText('success');
		Helper_JsonResponse::render();
            }
        }
        
        
        public function action_ajax_remove_photo_from_gallery()
        {
            $photo = ORM::factory('pet_photo', $this->request->post('id'));
            $feed  = ORM::factory('feed')->where('user_id', '=', $this->logget_user->id)
                                         ->where('pet_id', '=', $photo->pet_id)
                                         ->where('date_created', '=', $photo->date_created)
                                         ->find();
            if($feed->id)
              $feed->delete();
            
            @unlink(Kohana::$config->load('config')->get('pets.pictures').$photo->pet_id.'/'.$photo->name);
            $photo->delete();
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        




        public function action_tag()
        {
            Helper_Main::print_flex($this->request->param('id'));die();
        }
        
        
        
}