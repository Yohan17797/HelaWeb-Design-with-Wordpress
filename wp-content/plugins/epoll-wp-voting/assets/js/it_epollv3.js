/*it_epoll_js*/
jQuery.noConflict();
jQuery(document).ready(function($) {

	jQuery('.it_epoll_sys_show_voter_table tr').each(function(){
		var it_epoll_tbl = jQuery(this).find('.it_epoll_sys_show_voter');
		jQuery(this).find('.it_epoll_sys_show_voter_btn').on('click',function(){
			jQuery(it_epoll_tbl+' tr').each(function(){
				jQuery(this).slideToggle();
			});
		});
	});

	jQuery('.it_epoll_color-field').wpColorPicker();
	jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
		jQuery(this).find('#it_epoll_poll_option_rm_btn').click(function() {
			jQuery(it_ele_container).remove();
		});
	});	
	jQuery('.it_epoll_add_option_btn').click(function()
	{	
		var date = new Date();
		var components = [
			date.getYear(),
			date.getMonth(),
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			date.getMilliseconds()
		];

		var uniqid = components.join("");
		
		jQuery('#it_epoll_append_option_filed').append('<tr class="it_epoll_append_option_filed_tr"><td><table class="form-table"><tr><td>Option Name</td><td><input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" required/></td></tr><tr><td>Option Image</td><td><input type="url" class="widefat" id="it_epoll_poll_option_img" name="it_epoll_poll_option_img[]"/><input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="'+uniqid+'"/></td><td><input type="button" class="button" id="it_epoll_poll_option_btn" name="it_epoll_poll_option_btn" value="Upload"></td></tr><tr><td>Option Cover Image</td><td><input type="url" class="widefat" id="it_epoll_poll_option_cover_img" name="it_epoll_poll_option_cover_img[]" value=""/></td><td><input type="button" class="button" id="it_epoll_poll_option_ci_btn" name="it_epoll_poll_option_ci_btn" value="Upload"></td></tr><tr><td colspan="2"><input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove This Option"></td></tr></table></td></tr>');
		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
			jQuery(this).find('#it_epoll_poll_option_rm_btn').click(function() {
				jQuery(it_ele_container).remove();
			});
		});	
		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#it_epoll_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#it_epoll_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});
	});



		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#it_epoll_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#it_epoll_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});
});
