<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feed extends ORM 
{
    
  
    public static function getMyFriendsFeeds($my_id, $pet_id = false, $limit = 10, $offset = 0, $count = false)
    {
        $res = DB::select('users.firstname')
                  ->select('users.lastname')
                  ->select(array('pets.picture', 'image'))
                  ->select(array('feeds.id', 'feed_id'))
                  ->select('feeds.feed')
                  ->select('feeds.code_name')
                  ->select('feeds.date_created')
                  ->select('feeds.pet_id')
                  ->from('feeds')
                  ->join('pets')->on('pets.id', '=', 'feeds.pet_id')
                  ->join('users')->on('users.id', '=', 'feeds.user_id')
                  ->join('friendships')->on('friendships.friend_id', '=', 'users.id')
                  ->where('friendships.accepted', '=', 1)
                  ->where('friendships.user_id', '=', $my_id);

//        if($pet_id)
//          $res->where('feeds.pet_id', '=', $pet_id);
        
        if($count)
          $res = $res->execute()->count();
        else
          $res = $res->limit($limit)->offset($offset)->order_by('feeds.date_created', 'DESC')->as_object()->execute()->as_array();
        
        return $res;
        
    }
  
  
}