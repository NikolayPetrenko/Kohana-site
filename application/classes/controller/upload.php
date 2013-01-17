<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Upload extends My_UserController 
{
	public function before()
	{
		parent::before();
	}
        
        public function action_images()
	{   
            $uploaddir   = Kohana::$config->load('config')->get('temp.upload');
            
            $ext         = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename    = md5(time().Text::random('alnum', 6));
            
            Library_Image::getInstance()->setImage($_FILES['image']['tmp_name'])
                                        ->setDestinationImage($uploaddir.$filename)
                                        ->setSize('800x600')
                                        ->resize();
            
            Helper_JsonResponse::addData(array('file' => $filename.'.'.$ext));
            Helper_JsonResponse::addText('success');
            Helper_JsonResponse::render();
	}
        
        public function action_pdf()
        {
            $uploaddir   = Kohana::$config->load('config')->get('lost.pet.pdfs');
            $ext         = pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION);
            $filename    = md5(time().Text::random('alnum', 6));
            
            if(move_uploaded_file($_FILES['pdf']['tmp_name'], $uploaddir.$filename.'.'.$ext)){
              Model_Pet_Lost::setPDF($this->request->query('pet_id'), $filename.'.'.$ext);
              Helper_JsonResponse::addData(array('file' => $filename.'.'.$ext));
              Helper_JsonResponse::addText('success');
            }else{
              Helper_JsonResponse::addText('failure');
            }
            Helper_JsonResponse::render();
        }
        
}