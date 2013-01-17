<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Tags extends My_AdminController {
        
        public function before() {
            parent::before();
            Helper_AdminSiteBar::setActiveItem('tags');
            Stripe::setApiKey(Kohana::$config->load('stripe')->test['secret_key']);
        }

        public function action_list()
        { 
            Helper_Output::factory()
                          ->link_js('jquery.dataTables.min')
                          ->link_js('jquery.dataTables.pagination')
                          ->link_js('admin/tags/list')
                          ;
            
            $this->setTitle("Home page")
                  ->view('admin/tags/list')
                  ->render()
                  ;
        }
        
        public function action_getAjaxData()
        {
            $offset      = $this->request->query('iDisplayStart');
            $limit       = $this->request->query('iDisplayLength');
            
            $tags = ORM::factory('pet_tag');
            //part for add filters 
            $tags = $tags->limit($limit)->offset($offset)->order_by('date_created', 'desc')->find_all();
            
            $data['iTotalDisplayRecords'] =  ORM::factory('pet_tag')->count_all();
            $data['iTotalRecords']        =  $data['iTotalDisplayRecords'];
            
            if(count($tags)) {
		foreach ($tags as $key => $tag){
                    $tempArray      =   array();
                    $stripe         =   Stripe_Charge::retrieve($tag->stripe_token);
                    $tempArray[]    =   $tag->pet->owner->firstname . ' ' . $tag->pet->owner->lastname;
                    $tempArray[]    =   $tag->pet->name;
                    $tempArray[]    =   $stripe->__get('description');
                    
                    if($tag->qrcode)
                      $tempArray[]  =   "<img src=".Helper_Image::instance()->getClearCachePatch('pets.tags', $tag->qrcode, 'thumb', $tag->pet_id.'/') . " alt=''>";
                    else  
                      $tempArray[]  =   'Haven\'t tag';
                    
                    if($stripe->__get('paid'))
                      $tempArray[]  =   '<i class="icon-ok"></i>';
                    else
                      $tempArray[]  =   '<i class="icon-remove"></i>';
                    
                    if($stripe->__get('refunded'))
                      $tempArray[]  =   '<i class="icon-ok"></i> ($'.substr($stripe->__get('amount_refunded'), 0, 2).')';
                    else
                      $tempArray[]  =   '<i class="icon-remove"></i>';
                    
                    $tempArray[]    =    Helper_Main::formatMoney($stripe->__get('amount'));
                    
                    $tempArray[]    =   Helper_Output::siteDate($stripe->__get('created'));
                    $tempArray[]    =   '<a class="btn btn-mini btn-privacy" href="'.URL::site('admin/tags/pet_code/'.$tag->pet_id).'"><i class="icon-upload"></i> QR code</a>
                                         <a class="btn btn-mini btn-danger" onclick="javascript:list.refundCharge(\''.$tag->stripe_token.'\', this); return false;" href="#"><i class="icon-share-alt icon-white"></i> Refund Money</a>';
                    $data['aaData'][] = $tempArray;
                }
            }else{
               $data['aaData'] = array(); 
            }
            
            echo json_encode($data);
        }
        
        public function action_refunding_charge()
        {
           Stripe_Charge::retrieve($this->request->post('charge_id'))->refund();
           Helper_JsonResponse::addText('success');
           Helper_JsonResponse::render();
        }
        
        public function action_ajax_generate_and_upload_code()
        {
            $pet = ORM::factory('pet', $this->request->post('pet_id'));
            $qrcode_info = '';
            $qrcode_info = URL::base().$pet->id;
            
            if($qrcode_info)
            {
                $name = md5(time().Text::random('alnum', 6)).'.png';
                $pet->tag->qrcode = $name;
                $pet->tag->update();
                $dir = Kohana::$config->load('config')->get('pets.tags').$pet->id.'/';
                if(!is_dir($dir))
                  @mkdir($dir, 0777, TRUE);
                $fullname = $dir.$name;
                QRCode::instance()->png(json_encode($qrcode_info), $fullname);
                
                if($pet->owner->device_token){
                    $iPhoneMessage    = array('body'=> 'QR-code was generated for '.$pet->name, 'action-loc-key' => 'Show');
                    $notificationData = array('pet_id' => $pet->id, 'type' => 'qrcode');
                    Library_Iphonepush::instance()->setTokens(array($pet->owner->device_token))
                                                  ->setData($notificationData)
                                                  ->setMessage($iPhoneMessage)
                                                  ->openConnect()
                                                  ->send()
                                                  ->closeConnect();
                }
                
                Helper_JsonResponse::addData(array('qr_code' => substr($fullname, 1)));
                Helper_JsonResponse::addText('success');
            }
            else
            {
                Helper_JsonResponse::addText('falure');
            }
            Helper_JsonResponse::render();
        }
        
        public function action_pet_code()
        {
            $data['pet'] = ORM::factory('pet', $this->request->param('id'));
            Helper_Output::factory()->link_js('admin/tags/pet_code');
            $this->setTitle('Generate code')
                 ->view('admin/tags/pet_code', $data)
                 ->render();
        }

}
