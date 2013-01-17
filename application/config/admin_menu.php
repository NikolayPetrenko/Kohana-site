<?php defined('SYSPATH') or die('No direct access allowed.');
$data['admin_sitebar'] = array(
                                      "dashboard"   => array(
                                                      'title' 	=> 'Dashboard',
                                                      'url'    	=> 'admin/dashboard',
                                                      'icon'    => 'icon-list-alt',
                                                      'status'  => 1,
                                                      ),
                                      "users"       => array(
                                                      'title' 	=> 'Users',
                                                      'url'    	=> 'admin/users/list',
                                                      'icon'    => 'icon-user',
                                                      'status'  => 0,
                                                      ),
                                      "application" => array(
                                                      'title' 	=> 'Application',
                                                      'url'    	=> 'admin/application',
                                                      'icon'    => 'icon-pencil',
                                                      'status'  => 0,
                                                      ),
                                      "maps"        => array(
                                                      'title' 	=> 'Map Manager',
                                                      'url'    	=> 'admin/maps/list',
                                                      'icon'    => 'icon-globe',
                                                      'status'  => 0,
                                                      ),
                                      "tags"        => array(
                                                      'title' 	=> 'Order Tags ',
                                                      'url'    	=> 'admin/tags/list',
                                                      'icon'    => 'icon-qrcode',
                                                      'status'  => 0,
                                                      ),
                                      "settings"    => array(
                                                      'title' 	=> 'Site Settings',
                                                      'url'    	=> 'admin/settings',
                                                      'icon'    => 'icon-wrench',
                                                      'status'  => 0,
                                                      ),
                                      "tag_cost"    => array(
                                                      'title' 	=> 'Tag Cost',
                                                      'url'    	=> 'admin/settings/tag_cost',
                                                      'icon'    => 'icon-tag',
                                                      'status'  => 0,
                                                      )
                              );
return $data;