<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	/**
		* Emails
		*
		* @package     
		* @subpackage  Settings
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	include ('settings/'.$m_current.'.php');
	
	
?>


<div class="tab-box wow-admin">
	<ul class="tab-nav">
		<li><a href="#t1"><i class="fa fa-user"></i> to Admin</a></li>	
		<li><a href="#t2"><i class="fa fa-users"></i> to User</a></li>		
		
	</ul>
	
	<div class="tab-panels">
		<div id="t1">
			<?php include ('emails/admin.php'); ?>
		</div>
		<div id="t2">
			<?php include ('emails/user.php'); ?>
		</div>
				
	</div>
</div>



