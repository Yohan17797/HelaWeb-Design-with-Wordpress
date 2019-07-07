<?php if(isset($_REQUEST['section'])){?>
	<div class="wrap" style="position: relative;">
<h1>Settings <sub style="color:orange">PRO</sub></h1>
<table class="wp-list-table widefat fixed striped posts">
	<thead>
		<tr>
			<th>
				<a href="<?php echo admin_url('admin.php?page=it_epoll_system');?>" class=""><i class="dashicons dashicons-chart-bar"></i> View Results</a>
			</th>
			<th style="text-align: center;">
				<a href="<?php echo admin_url('post-new.php?post_type=it_epoll_poll');?>" class=""><i class="dashicons dashicons-chart-pie"></i> Create New Poll</a>
			</th>
			<th style="text-align: right;">
				<a href="https://infotheme.in/#support" class=""><i class="dashicons dashicons-sos"></i> Get Support</a>
			</th>
		</tr>
	</thead>
</table>
<div class="it_epoll_system_upgrade_pro">
	<div class="it_epoll_system_upgrade_pro_dotted_line"></div>

	<div class="dashicons dashicons-unlock it_epoll_system_upgrade_pro_icon"></div>
	<a href="https://infotheme.net/product/epoll-pro/" class="it_edb_submit it_epoll_system_upgrade_pro_btn">Upgrade to Pro for all Features</a>

</div>
<table class="wp-list-table widefat fixed striped posts">
	<thead>
		<tr>
			<th style="text-align: center;" colspan="2">
				<h2>Customize Plugin As You Want!</h2>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
				
				<table class="widefat" style="max-width: 720px; margin:40px auto;">
					<tr>
						<th style="text-align: center;" colspan="2">
							<h3>General Settings</h3>
							<hr>
						</th>
					</tr>
					<tr>
						<td>
							<label>Enable Unique Vote</label>
						
							<select name="" class="widefat">
							<option>No</option>
							<option>Yes</option>
						</select>
						</td>
						<td>
							<label>Disable Brading</label>
							<select name="" class="widefat">
							<option>No</option>
							<option>Yes</option>
						</select>
						</td>
					</tr>
					<tr>
						<td>
							<label>Show A Pole in Popup</label>
							<select name="" class="widefat">
							<option>No</option>
							<option>Yes</option>
						</select>
						</td>
					
						<td>
							<label>Enable OTP</label>
						
							<select name="" class="widefat">
							<option>No</option>
							<option>Email Based</option>
							<option>Mobile Based</option>
							<option>Both Type OTP</option>
						</select>
						</td>
					</tr>
					<tr>
						
						<td>
							<label>SMS API KEY</label>
						
							<input type="text" name="" class="widefat">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label>SENDER API URL</label>
						
							<input type="text" name="" class="widefat" value="" placeholder="https://smsapiexample.com/?mobile=[YOUR_PHONE]&message=[YOURMESSAGE]&api_key=[APIKEY]sendid=EXAMPLEID"> <br><br><a href="https://infotheme.in/#contact" target="_blank" class="button button-primary">Get SMS API<a>
						</td>
					</tr>
					<tr>
						<td>
							<label>VEIFICATION EMAIL TEXT</label>
						
							<textarea name="" class="widefat"></textarea>
						</td>
						<td>
							<label>VEIFICATION SMS TEXT</label>
						
							<textarea name="" class="widefat"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr>
							<label>REDEISGN CONTACT FORM</label>
							<table class="widefat">
								<thead>
									<tr>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_tel_num"><i class="dashicons dashicons-phone it_adm_ico_button_epoll"></i> Phone</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_name"><i class="dashicons 
dashicons-admin-users it_adm_ico_button_epoll"></i> NAME</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_email"><i class="dashicons 
dashicons-email it_adm_ico_button_epoll"></i> EMAIL</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_address"><i class="dashicons 
dashicons-location it_adm_ico_button_epoll"></i> ADDRESS</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_custom_text"><i class="dashicons 
dashicons-star-filled it_adm_ico_button_epoll"></i> CUSTOM FIELD</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_custom_textarea"><i class="dashicons 
dashicons-star-filled it_adm_ico_button_epoll"></i> CUSTOM TEXTAREA</button></td>
										<td><button type="button" class="button button-secondary" id="iteditor_epoll_voter_submit_btn"><i class="dashicons 
dashicons-migrate it_adm_ico_button_epoll"></i> SUBMIT BUTTON</button></td>
									</tr>
								</thead>
								<tbody>
									<tr">
										<td colspan="4">
											<textarea name="it_epoll_contact_builder_textarea" id="it_epoll_contact_builder_textarea" class="widefat" rows="12" placeholder='[VOTER_TEL_NUM label="Your Mobile Number"]
[VOTER_EMAIL label="Your Email"]
[VOTER_ADDRESS label="Your Address"]
[VOTER_SUBMIT_BTN label="Vote Now"]'></textarea>
										<button type="button" id="it_epoll_contact_builder_textarea_reset" class="button button-secondary"><i class="dashicons 
dashicons-update it_adm_ico_button_epoll"></i> Reset Design</button>
										</td>
										<td colspan="3">
											<div style="max-height: 300px; overflow-y:scroll;">
											<div class="it_epoll_contact_formbuilder_demo">
												<h3 style="text-align: center; color:#fff;">Preview</h3>
												<div class="it_epoll_contact_formbuilder_demo_content">
												</div>
											</div>
										</div>
										</td>
									</tr>
								</tbody>	
							</table>
						</td>
					</tr>
					<tr>
						<td style="text-align: center;" colspan="2">
							<hr>
							<button type="submit" name="" class="button button-primary">Save Settings</button>
							<button type="submit" name="" class="button button-secondary">Cancel Changes</button>
						</td>
					</tr>
			</td>
		</tr>
	</tbody>
	</table>
</div>
<?php 
}else{
include_once('it_epoll_results.php');
	?>

<?php }?>