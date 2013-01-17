<?php defined('SYSPATH') or die('No direct access allowed.');
$data = array (
	'Site Title' 		=> 'Unleashed | Web',
	'Site Keywords' 	=> 'pet, dog, cat, lost',
	'Site Description' 	=> 'The main functionality of Unleashed is to keep your pet safe by providing the pet with an identification tag that contains a QR code that users can scan to immediately notify the pet owner of the pets location.',
	'date.format'		=> 'm-d-Y',
        'lists.count'           => 50,
        'gallery.count'         => 21,
        'scpinner.url'          => './img/loading1.gif',
        'temp.upload'           => './images/temp/',
        'pets.pictures'         => './images/pets/',
        'unknown.pets.pics'     => './images/unknown_pets/',
        'pets.tags'             => './images/pet_tags/',
        'user.avatars'          => './images/users/',
        'location.pictures'     => './images/locations/',
        'pdf.path'              => './docs/pdfs/',
        'alerts.radius'         => 20
);

$data['main_menu']  = array(                                
        "home"	=> array(
                                        'name'          => "Home",
                                        'url'		=> "home",
                                        'status'	=> 0,
                                        'access'        => 'all'
                                ),       
        "contact"	=> array(
                                        'name'          => "Contact Us",
                                        'url'		=> "contact",
                                        'status'	=> 0,
                                        'access'        => 'all'
                                ),       
        "about"         => array(
                                       'name'           => "About Us",
                                        'url'		=> "about",
                                        'status'	=> 0,
                                        'access'        => 'all'
                                ),
        "map"          => array(
                                        'name'          => "Map",
                                        'url'    	=> 'map',
                                        'status'	=> 0,
                                        'access'        => 'user'
                                ),
        "friends"	=> array(
                                        'name'          => "Friends",
                                        'url'		=> "friends/unleashed",
                                        'status'	=> 0,
                                        'access'        => 'user'
                                ),
        "messages"	=> array(
                                        'name'          => "Messages",
                                        'url'		=> "messages",
                                        'status'	=> 0,
                                        'access'        => 'user'
                                ),  
        "feeds"        => array(
                                        'name'          => "Feeds",
                                        'url'		=> "feeds",
                                        'status'	=> 0,
                                        'access'        => 'user'
                                ),

        "alerts"        => array(
                                        'name'          => "Alerts",
                                        'url'		=> "alerts",
                                        'status'	=> 0,
                                        'access'        => 'user'
                                ),
        "alerts_map"    => array(
                                       'name'           => "Alerts Map",
                                        'url'		=> "map/alerts",
                                        'status'	=> 0,
                                        'access'        => 'user'
                                )
);

$data['account_tabs']  = array (                                
        "aboutme"           => array (
                                        'title'         => "About Me",
                                        'url'    	=> 'users/profile',
                                        'status'	=> 0,
                                     ),
        "mypets"            => array (
                                        'title'         => "My Pets",
                                        'url'		=> "pets/list",
                                        'status'	=> 0,
                                     ),                        
        "feeds"              => array (
                                        'title'         => "Feeds",
                                        'url'		=> "feeds",
                                        'status'	=> 0,
                                     ),
    
        "map"              => array (
                                        'title'         => "Map",
                                        'url'		=> "map",
                                        'status'	=> 0,
                                     ),                                     
    
        "unleashed"         => array (
                                        'title'         => "Unleashed Friends",
                                        'url'		=> "friends/unleashed",
                                        'status'	=> 0,
                                     ),
        "unleashed_users"   => array (
                                        'title'         => "Unleashed Users",
                                        'url'		=> "friends/users",
                                        'status'	=> 0,
                                     ),
        "facebook"          => array (
                                        'title'         => "Facebook Friends",
                                        'url'		=> "friends/facebook",
                                        'status'	=> 0,
                                     ),
        "twitter"           => array (
                                        'title'         => "Twitter Followers",
                                        'url'		=> "friends/twitter",
                                        'status'	=> 0,
                                     ),
        "myconfig"          => array (
                                        'title'         => "Settings",
                                        'url'		=> "settings",
                                        'status'	=> 0,
                                      )
                                );


$whitelist = array('localhost', '127.0.0.1','unleashed.loc');
if(in_array($_SERVER['HTTP_HOST'], $whitelist)){
	$data['siteURL'] = 'http://unleashed.loc/';
} else {
	$data['siteURL'] = 'http://irakeapp.com:8080/';
}

return $data;