<?php
/**
* Emails
*
* @package     
* @subpackage  Settings
* @copyright   Copyright (c) 2017, Dmytro Lobov
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

/* Admin */


$admin_email_content = array(
	'id'   => 'admin_email_content',
	'name' => 'admin_email_content',	
	'type' => 'editor',
	'val' => isset($param['admin_email_content']) ? $param['admin_email_content'] : 'Hello, admin <strong><p/>You have a new users</strong> <p/> <ul> <li>email: {email}</li> <li>first name: {fname}</li> <li>last name: {lname}</li> </ul>',	
);

/* User */


$user_email_content = array(
	'id'   => 'user_email_content',
	'name' => 'user_email_content',	
	'type' => 'editor',
	'val' => isset($param['user_email_content']) ? $param['user_email_content'] : 'Hello,<strong> <i>{fname}.</i></strong><p/>Thank you for registered. We hope that you will not be disappoint',	
);

?>