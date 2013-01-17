<?php defined('SYSPATH') or die('No direct script access.');
class Model_Breed extends ORM 
{
      protected $_belongs_to = array(
                                      'type'   => array(
                                                          'model'       => 'pet_type',
                                                          'foreign_key' => 'type_id',
                                                         ),
                                    );
}