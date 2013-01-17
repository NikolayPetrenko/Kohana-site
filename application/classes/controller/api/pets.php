<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Pets extends My_ApiController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_list()
	{
            $pets = array();
            foreach ($this->logget_user->pets->find_all() as $key => $pet){
                $pets[$key]['id']         = $pet->id;
                $pets[$key]['name']       = $pet->name;
                $pets[$key]['type_id']    = $pet->type_id;
                $pets[$key]['user_id']    = $pet->user_id;
                $pets[$key]['description']= $pet->description;
                $pets[$key]['breed_id']   = $pet->breed_id;
                $pets[$key]['text_status']= $pet->text_status;
                $pets[$key]['dob']        = Helper_Output::siteDateForOldDates($pet->dob);
                $pets[$key]['breed_name'] = $pet->breed->breed;
                //create flag for pet. Lost or not
                if($pet->finds->find()->pet_id)
                  $pets[$key]['isFind']   = true;
                //create flag for pet. Lost or not
                if($pet->lost->pet_id)
                  $pets[$key]['isLost']   = true;
                //create reference on qr-code if exist
                if($pet->tag->qrcode)
                    $pets[$key]['qrcode'] = URL::base().substr(Kohana::$config->load('config')->get('pets.tags'), 2).$pet->id.'/'.$pet->tag->qrcode;
                //create flag for pet. Isset tag or not
                if($pet->tag->pet_id)
                  $pets[$key]['isTag']    = true;
                //create reference on avatar if exist
                if($pet->picture)
                    $pets[$key]['picture'] = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture;
                
            }
            Helper_JsonResponse::addData(array('pets' => $pets));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
	}
        
        public function action_setTextStatus()
        {
            $pet = ORM::factory('pet',$this->request->post('pet_id'));
            $pet->text_status = $this->request->post('text_status');
            $pet->update();
            if($this->request->post('text_status')){
                $feed = $this->logget_user->firstname . ' change status for ' . $pet->name;
                Helper_Feed::factory($pet->owner->id, $pet->id)->setCodeName('change_pet_status')->setFeed($feed)->save();
            }
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }


        public function action_add()
	{
            if($this->request->post()){
                $_POST = Arr::map('HTML::chars', $this->request->post());
                $pet = ORM::factory('pet');
                if($this->request->post('id')) 
                {
                    $pet = $pet->where('id','=', $this->request->post('id'))->find();
                    //check this logget user pet or not
                    if($pet->owner->id != $this->logget_user->id)
                    {
                      Helper_JsonResponse::addText('failure');
                      Helper_JsonResponse::render();
                    }
                }
                    
                $pet->name         = $this->request->post('name');
                $pet->description  = $this->request->post('description');
                $pet->breed_id     = $this->request->post('breed_id');
                $pet->type_id      = $this->request->post('type_id');
                $pet->dob          = Helper_Input::changeDateFormat($this->request->post('dob'));
                $pet->picture      = $this->request->post('picture');
                $pet->user_id      = $this->logget_user->id;
                
                if($this->request->post('id')){
                    $pet->update();
                    Helper_Pet::tryCreateFolder($pet->id);
                    if($this->request->post('picture'))
                    {
                        Helper_Pet::move_pet_file($pet->id, $this->request->post('picture'));
                    }
                    
                    Helper_JsonResponse::addData(array('message' => 'Was Edit'));
                }else{
                    $pet->create();
                    Helper_Pet::tryCreateFolder($pet->id);
                    if($this->request->post('picture'))
                    {
                        Helper_Pet::move_pet_file($pet->id, $this->request->post('picture'));
                    }
                    $pet  = ORM::factory('pet', $pet->id);
                    $feed = 'Your friend '.$this->logget_user->firstname .' '. $this->logget_user->lastname.' add new '.$pet->type->type.' by name '.$pet->name;
                    Helper_Feed::factory($this->logget_user->id, $pet->id)->setCodeName('add_pet')->setFeed($feed)->save();
                    Helper_JsonResponse::addData(array('id' => $pet->id , 'message' => 'Was Saved'));
                }
                
                Helper_JsonResponse::addText('success');
                Helper_JsonResponse::render();
                
            }
	}
        
        public function action_clearPetDetails(){
          ORM::factory('pet_lost')->clearDetails($this->request->post('pet_id'));
          Helper_JsonResponse::addText('success');
          Helper_JsonResponse::render();
        }


        public function action_getPetsByUserID()
        {
            if($this->request->post()){
                $user = ORM::factory('user', $this->request->post('id'));
                $userPets = array();
                foreach ($user->pets->find_all() as $key=>$pet){
                    $userPets[$key]            = $pet->as_array();
                    $userPets[$key]['picture'] = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$userPets[$key]['id'].'/'.$userPets[$key]['picture'];
                }

                Helper_JsonResponse::addData(array('pets' => $userPets));
                Helper_JsonResponse::addText('success');
                Helper_JsonResponse::render();
            }
        }
        
        public function action_AllPets()
        {
            $limit        = $this->request->post('limit');
            $offset       = $this->request->post('offset');
            $sType        = $this->request->post('sType');
            
            $pets = Model_Pet::searchAllPets($this->logget_user->id, $limit, $offset, $sType);
            
            Helper_JsonResponse::addData(array('pets' => $pets));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
        
        public function action_getPetProfile()
        {
           $pet = ORM::factory('pet',$this->request->post('id'));
           
           if($pet->id){
              $petObjectForMobile                = new stdClass();
              $petObjectForMobile->name          = $pet->name;
              if($pet->picture)
                  $petObjectForMobile->picture   = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture;
              $petObjectForMobile->age           = Helper_Output::getAge($pet->dob);
              $petObjectForMobile->type          = $pet->type->type;
              $petObjectForMobile->owner_id      = $pet->owner->id;
              $petObjectForMobile->owner         = $pet->owner->firstname;
              $petObjectForMobile->breed         = $pet->breed->breed;
              $petObjectForMobile->description   = $pet->description;

              Helper_JsonResponse::addData(array('pet' => $petObjectForMobile));
              Helper_JsonResponse::addText('success');
              Helper_JsonResponse::render();
           }
           
        }
        
        public function action_setTags()
        {
            //save contact info
            if($this->request->post('address'))
              $this->logget_user->address         = $this->request->post('address');
            if($this->request->post('city'))
              $this->logget_user->city            = $this->request->post('city');
            if($this->request->post('state'))
              $this->logget_user->state           = $this->request->post('state');
            if($this->request->post('zip'))
              $this->logget_user->zip             = $this->request->post('zip');
            if($this->request->post('primary_phone'))
              $this->logget_user->primary_phone   = $this->request->post('primary_phone');
            if($this->request->post('secondary_phone'))
              $this->logget_user->secondary_phone = $this->request->post('secondary_phone');
            $this->logget_user->update();
            
            $pet_tags = json_decode($this->request->post('petIDs'));
            foreach ($pet_tags as $item){
                $pet = ORM::factory('pet', $item->id);
                if(!$pet->tag->pet_id){
                  Stripe::setApiKey(Kohana::$config->load('stripe')->test['secret_key']);
                  $res = Stripe_Charge::create(array(
                                                      "amount"      => ORM::factory('setting', array('key' => 'tag_cost'))->value,
                                                      "currency"    => "usd",
                                                      "card"        => $item->token,
                                                      "description" => "Charge from ".$this->logget_user->firstname." ".$this->logget_user->lastname." (".$this->logget_user->id.") for ".$pet->name." (".$pet->id.") tag"
                                                    )
                                              );

                  $pet->tag->pet_id       = $item->id;
                  $pet->tag->stripe_token = $res->__get('id');
                  $pet->tag->save();
                }
            }
            
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
            
        }
        
        
        public function action_setPetPhotoInGallery()
        {
           $pet_photo         = ORM::factory('pet_photo');
           $pet_photo->pet_id = $this->request->post('pet_id');
           $pet               = ORM::factory('pet', $this->request->post('pet_id'));
           $pet_photo->name   = $this->request->post('image');
           
           @copy(Kohana::$config->load('config')->get('temp.upload').$this->request->post('image'), Kohana::$config->load('config')->get('pets.pictures').$pet_photo->pet_id.'/'.$this->request->post('image'));
           @unlink(Kohana::$config->load('config')->get('temp.upload').$this->request->post('image'));
           $photo_id          = $pet_photo->create();
           $feed              = $this->logget_user->firstname . ' add new photo for ' . $pet->name;
           Helper_Feed::factory($this->logget_user->id, $pet_photo->pet_id)->setCodeName('upload_photo')->setFeed($feed)->save();
           Helper_JsonResponse::addData(array('id' => $photo_id->id, 'path' => URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet_photo->pet_id.'/'.$this->request->post('image')));
           Helper_JsonResponse::addText('success');
           Helper_JsonResponse::render();
           
        }
        
        public function action_getGallery()
        {
           $pet    = ORM::factory('pet', $this->request->post('pet_id'));
           $photos = array();
           foreach ($pet->photos->limit($this->request->post('limit'))->offset($this->request->post('offset'))->find_all() as $item){
             $photos[] = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$item->name;
           }
           
           Helper_JsonResponse::addData(array('photos' => $photos ));
           Helper_JsonResponse::addText('success');
           Helper_JsonResponse::render();
        }
        
        
        public function action_setLostInfo()
        {
            $pet = ORM::factory('pet', $this->request->post('pet_id'));
            if($pet->id){
                $this->logget_user->address         = $this->request->post('address');
                $this->logget_user->primary_phone   = $this->request->post('primary_phone');
                $this->logget_user->secondary_phone = $this->request->post('secondary_phone');
                $lost = json_decode($this->request->post('lost'));
                
                $this->logget_user->update();
                ORM::factory('pet_lost')->insert_lost_data((array)$lost, $pet->id, $this->logget_user);
                
                Helper_JsonResponse::addText('success');
            }else{
                Helper_JsonResponse::addText('failure');
            }
            Helper_JsonResponse::render();
        }
        
        public function action_getLostInfoByTag()
        {
            $pet = ORM::factory('pet', $this->request->post('pet_id'));
            if($pet->id){
                $qrcode_info = array();
                $qrcode_info['pet_name']              = $pet->name;
                $qrcode_info['pet_age']               = Helper_Output::getAge($pet->dob);
                $qrcode_info['pet_image']             = URL::base().substr(Kohana::$config->load('config')->get('pets.pictures'), 2).$pet->id.'/'.$pet->picture;
                $qrcode_info['pet_type']              = $pet->type->type;
                $qrcode_info['pet_breed']             = $pet->breed->breed;
                $qrcode_info['owner_id']              = $pet->owner->id;
                $qrcode_info['owner_full_name']       = $pet->owner->firstname . ' ' . $pet->owner->lastname;
                $qrcode_info['owner_email']           = $pet->owner->email;
                $qrcode_info['owner_state']           = $pet->owner->state;
                $qrcode_info['owner_city']            = $pet->owner->city;
                $qrcode_info['owner_zip']             = $pet->owner->zip;
                $qrcode_info['owner_address']         = $pet->owner->address;
                $qrcode_info['owner_primary_phone']   = $pet->owner->primary_phone;
                $qrcode_info['owner_secondary_phone'] = $pet->owner->secondary_phone;
                if($pet->lost->pet_id)
                    $qrcode_info['pet_isLost']        = true;
                Helper_JsonResponse::addData(array('info' => $qrcode_info));
                Helper_JsonResponse::addText('success');
            }else{
                Helper_JsonResponse::addText('failure');
            }
            Helper_JsonResponse::render();
        }
        
        public function action_setPetFind()
        {
          $pet_id           = $this->request->post('pet_id');
          $pet              = ORM::factory('pet', $pet_id);
          $_POST['user_id'] = $this->logget_user->id;
          $address          = $this->request->post('address');
          $latitude         = $this->request->post('latitude');
          $longitude        = $this->request->post('longitude');
          if($pet->id && $address && $latitude && $longitude){
            $pet_find_id = ORM::factory('pet_find')->setDetails($_POST, $pet->id);
            
            Mailer::factory('user')->send_petfind(array(
                                                        'user'	=> array(
                                                                        "name" 		=> $pet->owner->firstname . ' ' . $pet->owner->lastname,
                                                                        'email'		=> $pet->owner->email,
                                                                        'link'		=> URL::site('alerts/details/find/'.$pet_find_id)
                                                                        )
                                                    ));
            
            $iPhoneMessage    = array('body'=> $this->logget_user->firstname.' ('.$this->logget_user->email.') find your '.$pet->name, 'action-loc-key' => 'Show');
            $notificationData = array('pet_find_id' => $pet_find_id, 'name' => $pet->name , 'type' => 'find');
            if($pet->owner->device_token){ //if owner has  devise token
                Library_Iphonepush::instance()->setTokens(array($pet->owner->device_token))
                                              ->setData($notificationData)
                                              ->setMessage($iPhoneMessage)
                                              ->openConnect()
                                              ->send()
                                              ->closeConnect();
            }
            
            $feed = $this->logget_user->firstname.' '.$this->logget_user->lastname.' find '.$pet->name . '!!';
            Helper_Feed::factory($this->logget_user->id, $pet->id)->setCodeName('find_pet')->setFeed($feed)->save();
            Helper_JsonResponse::addText('success');
          }else{
            Helper_JsonResponse::addText('failure');
          }
          Helper_JsonResponse::render();
        }
        
        public function action_setUnknownPet()
        {
            $unknown                    = json_decode($this->request->post('unknown_info'));
            $unknown                    = (array)$unknown;
            $unknown['user_id']         = $this->logget_user->id;
            ORM::factory('user_unknown')->setPoint($unknown);
            
            
            $this->logget_user->address         = $this->request->post('user_address');
            $this->logget_user->primary_phone   = $this->request->post('primary_phone');
            $this->logget_user->secondary_phone = $this->request->post('secondary_phone');
            $this->logget_user->update();

            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
        }
}