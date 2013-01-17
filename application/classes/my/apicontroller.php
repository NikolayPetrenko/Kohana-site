<?php defined('SYSPATH') or die('No direct script access.');

class My_ApiController extends Controller
{
	public function before()
	{
		parent::before();
                $this->logget_user = Helper_iAuth::instance()->getLoggedUser();
                
                if(!$this->logget_user){
                  Helper_JsonResponse::addError(Helper_Output::getErrorCode("Could not authenticate with OAuth"));
                  Helper_JsonResponse::addText('failure');
                  Helper_JsonResponse::render();
                }
                
	}
}
