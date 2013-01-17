<?php
class Helper_Twitter
{
      public $twitter_obj = '';
      public $account     = '';

      public function __construct($token, $secret) {
            $this->twitter_obj = new EpiTwitter(Kohana::$config->load('social')->get('twitter.consumer.key'), 
                                                Kohana::$config->load('social')->get('twitter.consumer.secret'),
                                                $token, 
                                                $secret);
            $userInfo      = $this->twitter_obj->get_accountVerify_credentials();
            $this->account = $userInfo->response;
	    $this->my_id    = $userInfo->response['id'];
      }
      
      
      public static function instance($token = false, $secret = false)
      {
          if(!$token)
            $token 	= Cookie::get('oauth_token');
          if(!$secret)
            $secret     = Cookie::get('oauth_token_secret');
          return new Helper_Twitter($token, $secret);
      }
      
      public function getFollowers($user_id , $cursor = -1)
      {
          
          $trends_url = "http://api.twitter.com/1/statuses/followers/".$this->my_id.".json?cursor=".$cursor;
          $response = $this->getCurlResByUrl($trends_url);
          
          if(!empty($response)){
              foreach($response['users'] as $key => $friend){
                  $followers['users'][$key]['id']           = $friend['id'];
                  $followers['users'][$key]['avatar']       = $friend['profile_image_url'];
                  $followers['users'][$key]['url']          = $friend['screen_name'];
                  $followers['users'][$key]['name']         = $friend['name'];
                  $followers['users'][$key]['unleashed_id'] = ORM::factory('user')->where('twitter_id', '=', $friend['id'])->find()->id;
                  
                  if($followers['users'][$key]['unleashed_id']){
                      $friendship    = ORM::factory('friendship')->get_friendship_status($followers['users'][$key]['unleashed_id'], $user_id);
                      if($friendship)
                        $followers['users'][$key]['friendship']   = $friendship->accepted;
                      else
                        $followers['users'][$key]['friendship']   = null;
                  }
                  
              }
                $followers['next_cursor']     = $response['next_cursor'];
                $followers['previous_cursor'] = $response['previous_cursor'];
          }else{
              $followers = array();
          }
          
          return $followers;
          
      }
      
      
      public function getLimitStatus()
      {
        $trends_url = "https://api.twitter.com/1/account/rate_limit_status.json";
        $response = $this->getCurlResByUrl($trends_url);
        return $response['reset_time'];
      }
      
      public function getFollowersNumber()
      {
          $trends_url = "https://api.twitter.com/1/users/show.json?user_id=".$this->my_id;
          $response = $this->getCurlResByUrl($trends_url);
          return $response['followers_count'];
      }



      public function getAccount()
      {
          return $this->account;
      }

      
      public function sendNotificationForAllFollowers($text)
      {
        $trends_url = "http://api.twitter.com/1/statuses/followers/".$this->my_id.".json";
        $response   = $this->getCurlResByUrl($trends_url);
        $this->twitter_obj->useAsynchronous(true);
        foreach($response as $item){
            $this->twitter_obj->post('/direct_messages/new.json', 
                                     array('user' => $item['screen_name'], 
                                           'text' => $text)
                                    );
        }
      }
      
      public function getCurlResByUrl($url)
      {
          $ch = curl_init(); 
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $curlout = curl_exec($ch);
          curl_close($ch);
          return json_decode($curlout, true);
      }




}


