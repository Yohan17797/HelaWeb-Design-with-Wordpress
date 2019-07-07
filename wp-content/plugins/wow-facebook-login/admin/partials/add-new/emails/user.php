<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	/**
		* Email to User
		*
		* @package     Settings
		* @subpackage  Emails
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
?>


<div class="itembox">
	<div class="item-title">
		<h3>Email to User</h3>
	</div>
	<div class="wow-admin-col wow-wrap">
		<div class="wow-admin-col-12">
			<a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> <input type="checkbox" disabled>  <label for="wow_user_email">Include email to user</label> 
			
		</div>
		<div class="wow-admin-col-12">
			<a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> From Name: <br/>
			<input type="text" disabled value="Wow-Company"> 
		</div>
		<div class="wow-admin-col-12">
			<a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> From Email: <br/>
			<input type="text" disabled value="email@example.com">
		</div>
		<div class="wow-admin-col-12">
			<a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> Email Subject: <br/>
			<input type="text" disabled value="Congratulations, you have successfully login">
		</div>
		<div class="wow-admin-col-12">
			<a href='admin.php?page=<?php echo $this->slug;?>&tab=pro' title="Only Pro version"><span class="dashicons dashicons-lock" style="color:#37c781;"></span></a> Email Content: <br/>
			<?php echo self::create_option($user_email_content);?>
		</div>
	</div>
	
	<div class="wow-admin-col">
		<div class="wow-admin-col-12">			
			Enter the text that is sent to email to users after subscribing. HTML is accepted. Available template tags.
			<p />
			<i>{email} - User email</i><br/>
			<i>{fname} - User First Name </i><br/>
			<i>{lname} - User Last Name </i><br/>			
			<i>{login} - User login </i><br/>				
		</div>
	</div>
	
	
</div>