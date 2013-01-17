<?php 
return array(
    'native' => array(
        'name' => 'session_name',
        'lifetime' => 43200,
    ),
    'cookie' => array(
        'name' => 'cookie_name',
        'encrypted' => TRUE,
        'lifetime' => 43200,
    ),
    
    'database' => array(
        'name' => 'session_database',
        'encrypted' => TRUE,
        'lifetime' => 43200,
        'group' => 'default',
        'table' => 'sessions',
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);