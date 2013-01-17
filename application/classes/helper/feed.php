<?php
class Helper_Feed
{
    public $feed    = '';
    public $user_id = 0;
    public $pet_id  = 0;
    
    public function __construct($user_id, $pet_id = 0)
    {
        $this->user_id = $user_id;
        $this->pet_id  = $pet_id;
    }
    
    public static function factory($type, $link_id)
    {
        return new Helper_Feed($type, $link_id);
    }

    public function setFeed($feed = '')
    {
        $this->feed = $feed;
        return $this;
    }
    
    public function setCodeName($code_name = '')
    {
        $this->code_name = $code_name;
        return $this;
    }
    
    public function clearOldThisFeeds()
    {
        $res = ORM::factory('feed')->where('user_id', '=', $this->user_id)
                            ->where('pet_id', '=', $this->pet_id)
                            ->where('code_name', '=', $this->code_name)->find_all();
        foreach ($res as $item){
          $item->delete();
        }
        
        return $this;
        
    }

    public function save()
    {
        $feed = ORM::factory('feed');
        $feed->user_id    = $this->user_id;
        $feed->pet_id     = $this->pet_id;
        $feed->code_name  = $this->code_name;
        $feed->feed       = $this->feed;
        $feed->create();
        return true;
    }
    
}