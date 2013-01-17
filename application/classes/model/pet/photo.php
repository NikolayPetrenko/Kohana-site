<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet_Photo extends ORM 
{
    protected $_belongs_to = array('pet'  => array('model' => 'pet'));
}