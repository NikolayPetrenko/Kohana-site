<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pet_Tag extends ORM 
{
    protected $_primary_key = 'pet_id';
    protected $_belongs_to = array('pet'  => array('model' => 'pet'));
}