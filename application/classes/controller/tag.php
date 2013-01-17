<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Tag extends My_LoggetUserController {

      public function before() {
          parent::before();
      }

      public function action_purchase()
      {
          $data['pet'] = ORM::factory('pet', $this->request->param('id'));
          $data['me']  = $this->logget_user;
          if($data['pet']->owner->id != $data['me']->id && !$data['pet']->tag->stripe_token)
            $this->request->redirect ('');
          
          if($this->request->post()){
            $this->logget_user->values(array_values($this->request->post('user')), array_keys($this->request->post('user')))->update();
            
            $pet = ORM::factory('pet', $this->request->param('id'));
            Stripe::setApiKey(Kohana::$config->load('stripe')->test['secret_key']);
            $res = Stripe_Charge::create(array(
                                                "amount"      => ORM::factory('setting', array('key' => 'tag_cost'))->value,
                                                "currency"    => "usd",
                                                "card"        => $this->request->post('stripe_token'),
                                                "description" => "Charge from ".$this->logget_user->firstname." ".$this->logget_user->lastname." (".$this->logget_user->id.") for ".$pet->name." (".$pet->id.") tag"
                                              )
                                        );

            $pet->tag->pet_id       = $this->request->param('id');
            $pet->tag->stripe_token = $res->__get('id');
            $pet->tag->save();
            $this->request->redirect('home/thank_you');
          }
          
          Helper_Output::factory()->link_js('jquery.validate.min')
                                  ->link_js('https://js.stripe.com/v1/')
                                  ->link_js('frontend/tag/purchase');
          
          $this->setTitle("MAP")
                  ->view('tag/purchase', $data)
                  ->render()
                  ;
      }
        
}
