<?php

$whitelist = array('localhost', '127.0.0.1','unleashed.loc');
if(!in_array($_SERVER['HTTP_HOST'], $whitelist)){
	//production keys
   return array(
                //Anton First 
   		//'twitter.consumer.key'		=>	'02WAM7Hkv0THZrXyRydBA',
		//'twitter.consumer.secret'	=>	'cZp5NtqO2qU1RGEj2fTKmfRueOVCkhlFtIBOWfXGs',
                //Vitalik
                'twitter.consumer.key'		=>	'NpVSGeGsztdyQb3AL41Q',
		'twitter.consumer.secret'	=>	'hCnSQr3ddRPUhNcDxfqoJDSc6QQyyb6DYyq8HCyTs',
   	);
} else {
	return array(
   		'twitter.consumer.key'          =>	'GPELmcSviwnq9XoGbcsWUQ',
		'twitter.consumer.secret'       =>      'LuGFfrbEmEoWKJBUBXRgtQFJb9XbAKa6NYx16GPc5vs',
   	);
}
