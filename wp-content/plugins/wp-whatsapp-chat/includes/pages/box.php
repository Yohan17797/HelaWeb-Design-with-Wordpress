<form method="post" action="options.php">
  <?php settings_fields(sanitize_key(QLWAPP_DOMAIN . '-group')); ?>
  <?php do_settings_sections(sanitize_key(QLWAPP_DOMAIN . '-group')); ?>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><?php esc_html_e('Disable', 'wp-whatsapp-chat'); ?></th>
        <td>
          <select name="<?php echo esc_attr(QLWAPP_DOMAIN); ?>[box][enable]" class="qlwapp-select2">
            <option value="yes" <?php selected($qlwapp['box']['enable'], 'yes'); ?>><?php esc_html_e('Enable contact box', 'wp-whatsapp-chat'); ?></option>
            <option value="no" <?php selected($qlwapp['box']['enable'], 'no'); ?>><?php esc_html_e('Disable contact box', 'wp-whatsapp-chat'); ?></option>
          </select>
        </td>
      </tr>
      <tr>
        <th scope="row"><?php esc_html_e('Header', 'wp-whatsapp-chat'); ?></th>
        <td>
          <?php wp_editor($qlwapp['box']['header'], 'qlwapp_box_header', array('editor_height' => 200, 'textarea_name' => esc_attr(QLWAPP_DOMAIN) . '[box][header]')); ?>
        </td>
      </tr>
      <tr>
        <th scope="row"><?php esc_html_e('Footer', 'wp-whatsapp-chat'); ?></th>
        <td>            
          <?php wp_editor($qlwapp['box']['footer'], 'qlwapp_box_footer', array('editor_height' => 200, 'textarea_name' => esc_attr(QLWAPP_DOMAIN) . '[box][footer]')); ?>
        </td>
      </tr>
      <tr>
        <th scope="row"><?php esc_html_e('Response', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input type="text" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[chat][response]'); ?>" placeholder="<?php echo esc_html($this->defaults['chat']['response']); ?>" value="<?php echo esc_attr($qlwapp['chat']['response']); ?>" class="qlwapp-input"/>
          <p class="description"><?php esc_html_e('Write a response text.', 'wp-whatsapp-chat'); ?></p>  
        </td>
      </tr>
      <tr>
        <th scope="row">
          <?php esc_html_e('Contacts', 'wp-whatsapp-chat'); ?>
        </th>
        <td>
          <table id="qlwapp-contact-form" class="form-table widefat hidden" data-editindex="-1">
            <?php $this->contact_table(); ?>
            <tfoot>
              <tr>
                <td colspan="2">
                  <?php submit_button(esc_html__('Save Contact', 'wp-whatsapp-chat'), 'primary', null, false, array('id' => 'btn-save-contact')); ?>
                  <span class="spinner"></span>
                </td>
              </tr>
            <tfoot>
          </table>
          <div class="submit qlwapp-premium-field">
            <?php submit_button(esc_html__('Add Contact', 'wp-whatsapp-chat'), 'secondary', null, false, array('id' => 'btn-add-contact')); ?>
            <p class="description"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>    
          </div>
          <table id="qlwapp-contacts-table" class="form-table widefat striped">
            <thead>
              <tr>
                <td><b><?php esc_html_e('Avatar', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Phone', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Name', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Label', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Message', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Chat', 'wp-whatsapp-chat'); ?></b></td>
                <td><b><?php esc_html_e('Actions', 'wp-whatsapp-chat'); ?></b></td>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <?php submit_button() ?>
</form>
<script>
  (function($) {
  let qlwapp_contacts = <?php echo json_encode($qlwapp['contacts']) ?>;
  let tableBody = document.querySelector('#qlwapp-contacts-table tbody');
  $(document).on('click', '#btn-edit-contact', function() {
  esc_html_edit_contact(this.dataset.cid);
  return false;
  });
  $('#btn-save-contact').click(function(e) {
  e.preventDefault();
  let contact = {

  firstname: $('#qlwapp-contact-form #cfirstname').val(),
          lastname: $('#qlwapp-contact-form #clastname').val(),
          phone: $('#qlwapp-contact-form #cphone').val(),
          label: $('#qlwapp-contact-form #clabel').val(),
          message: $('#qlwapp-contact-form #cmessage').val(),
          chat: $('#qlwapp-contact-form .cchat:checked').val(),
          avatar: $('#qlwapp-contact-form #cavatar').val()
  }

  let index = document.querySelector('#qlwapp-contact-form').dataset.editindex;
  if (index != '-1') {
  qlwapp_contacts[parseInt(index)] = contact;
  } else {
  qlwapp_contacts.push(contact);
  }
  qlwapp_show_contacts();
  $('#qlwapp-contact-form').slideToggle();
  return false;
  });
  $(document).on('click', '#btn-delete-contact', function () {
  let index = this.dataset.cid;
  if (!qlwapp_contacts[index])
          return false;
  qlwapp_contacts.splice(index, 1);
  qlwapp_show_contacts();
  return false;
  });
  function qlwapp_show_contacts() {
  tableBody.innerHTML = '';
  let i = 0;
  for (let contact of qlwapp_contacts) {
  let tr = document.createElement('tr');
  tr.dataset.cid = i;
  let td1 = document.createElement('td');
  let td2 = document.createElement('td');
  let td3 = document.createElement('td');
  let td4 = document.createElement('td');
  let td5 = document.createElement('td');
  let td6 = document.createElement('td');
  let tda = document.createElement('td');
  let avatar = contact.avatar ? contact.avatar : 'https://www.gravatar.com/avatar/00000000000000000000000000000000';
  //td0.innerHTML = (i + 1);
  tda.innerHTML = '<img class="qlwapp-avatar" src="' + avatar + '" alt="" width="100" height="100" />' +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][avatar]" value="' + avatar + '" />';
  td1.innerHTML = contact.phone +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][phone]" value="' + contact.phone + '" />';
  td2.innerHTML = contact.firstname + ' ' + contact.lastname +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][firstname]" value="' + contact.firstname + '" />' +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][lastname]" value="' + contact.lastname + '" />';
  td3.innerHTML = contact.label +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][label]" value="' + contact.label + '" />';
  td4.innerHTML = contact.message +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][message]" value="' + contact.message + '" />';
  td5.innerHTML = (Number(contact.chat) ? 'Enabled' : 'Disabled') +
          '<input type="hidden" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts]'); ?>[' + i + '][chat]" value="' + Number(contact.chat) + '" />';
  td6.innerHTML = '<a href="javascript:;" id="btn-delete-contact" data-cid="' + i + '">Remove</a> | ' +
          '<a href="javascript:;" id="btn-edit-contact" data-cid="' + i + '">Edit</a>';
  //tr.appendChild(td0);
  tr.appendChild(tda);
  tr.appendChild(td1);
  tr.appendChild(td2);
  tr.appendChild(td3);
  tr.appendChild(td4);
  tr.appendChild(td5);
  tr.appendChild(td6);
  tableBody.appendChild(tr);
  i++;
  }
  }

  function esc_html_edit_contact(index) {
  let contact = qlwapp_contacts[index] || null;
  if (!contact)
          return false;
  document.querySelector('#qlwapp-contact-form').dataset.editindex = index;
  $('#qlwapp-contact-form #cfirstname').val(contact.firstname);
  $('#qlwapp-contact-form #clastname').val(contact.lastname);
  $('#qlwapp-contact-form #cphone').val(contact.phone);
  $('#qlwapp-contact-form #clabel').val(contact.label);
  $('#qlwapp-contact-form .cchat[value="1"]').attr('checked', contact.chat);
  $('#qlwapp-contact-form .cchat[value="0"]').attr('checked', !contact.chat);
  $('#qlwapp-contact-form #cmessage').val(contact.message);
  $('#qlwapp-contact-form #cavatar').val(contact.avatar);
  $('#qlwapp-contact-form #cavatar-img').attr('src', contact.avatar);
  $('#qlwapp-contact-form').slideToggle();
  }
  qlwapp_show_contacts();
  })(jQuery);
</script>