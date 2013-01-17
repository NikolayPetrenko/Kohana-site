<?php
class Helper_Facebook
{
      public $facebook = '';


      public function __construct()
      {
            $this->facebook = FacebookAuth::factory()->getFacebook();
      }
      
      
      public static function instance()
      {
          return new Helper_Facebook();
      }
      
      public function getFriends($access_token, $limit, $offset, $user_id = false)
      {
          
          $test = 'https://graph.facebook.com/fql?q=SELECT+uid+,+name+,+pic_square+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1=me()+)+ORDER+BY+name+ASC+limit+'.$limit.'+offset+'.$offset.'+&access_token=' . $access_token;
          $res = $this->getCurlResByUrl($test);
          $res = json_decode($res, true);
          foreach ($res['data'] as $key=>$item){
              $FullFriens[$key]['id']     = $item['uid'];
              $FullFriens[$key]['avatar'] = $item['pic_square'];
              $FullFriens[$key]['name']   = $item['name'];
              $FullFriens[$key]['unleashed_id'] = ORM::factory('user')->where('facebook_id', '=', $item['uid'])->find()->id;
              if($FullFriens[$key]['unleashed_id']){
                  $friendship    = ORM::factory('friendship')->get_friendship_status($FullFriens[$key]['unleashed_id'], $user_id);
                  if($friendship)
                    $FullFriens[$key]['friendship']   = $friendship->accepted;
                  else
                    $FullFriens[$key]['friendship']   = null;
              }
          }
          
          return $FullFriens;
          
      }
      
      public function regenerateFacebookToken($exist_token)
      {
          $output = $this->getCurlResByUrl("https://graph.facebook.com/oauth/access_token?client_id=".Kohana::$config->load('facebook')->appId."&client_secret=".Kohana::$config->load('facebook')->secret."&grant_type=fb_exchange_token&fb_exchange_token=".$exist_token);
          parse_str($output, $output);
          return $output;
      }
      
      public function getFriendsNumber($access_token)
      {
          $res  = json_decode($this->getCurlResByUrl('https://graph.facebook.com/me/friends?access_token=' . $access_token));
          return count($res->data);
      }
      
      
      public function getCurlResByUrl($url)
      {
          $ch = curl_init($url); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_HEADER, 0); 
          curl_setopt($ch, CURLOPT_ENCODING , ""); 
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
          $output = curl_exec($ch); 
          curl_close($ch); 
          return $output;
      }
      
      
//      public function sendNotificationAboutLost($token, $feed)
//      {
//          $this->facebook->setAccessToken($token);
//          $friends  = $this->facebook->api('/me/friends/');
//          foreach ($friends['data'] as $item){
//               $this->facebook->api('/'. $item['id'] .'/feed', 'post', array(
//                                                                                  'access_token' => $token, 
//                                                                                  'message' => $feed,
//                                                                                  'picture' => '',
//                                                                                  'link' => URL::base(),
//                                                                                  'auto_publish' => false,
//                                                                                  'name' => 'Pet',
//                                                                                  'description' => 'Unleashed'
//                                                                                )
//              );  
//          }
//      }
      
      public function sendNotificationForAllFriends($token, $data = array())
      {
          $this->facebook->setAccessToken($token);
          $friends  = $this->facebook->api('/me/friends/');
          if($token && !empty($data)){
              $post = array_merge($data, array('access_token' => $token));
              foreach ($friends['data'] as $item){
                  $this->facebook->api('/'. $item['id'] .'/feed', 'post', $post);
              }
          }
      }




}


