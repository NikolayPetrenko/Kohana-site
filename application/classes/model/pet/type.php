<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet_Type extends ORM 
{
  protected $_has_many = array(
                               'breeds'	=> array('model' => 'breed')
                              );
  
  
}