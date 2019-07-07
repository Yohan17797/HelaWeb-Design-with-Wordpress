<form method="post" action="options.php">
  <?php settings_fields(sanitize_key(QLWAPP_DOMAIN . '-group')); ?>
  <?php do_settings_sections(sanitize_key(QLWAPP_DOMAIN . '-group')); ?>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><?php esc_html_e('Background', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="text" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][brand]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['brand']); ?>" />
        </td>
      </tr>
      <tr>
        <th scope="row"><?php esc_html_e('Color', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="text" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][text]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['text']); ?>" />
        </td>
      </tr>      
      <tr class="qlwapp-premium-field">
        <th scope="row"><?php esc_html_e('Link', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="link" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][link]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['link']); ?>" />
          <p class="description"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>        
        </td>
      </tr>      
      <tr class="qlwapp-premium-field">
        <th scope="row"><?php esc_html_e('Message', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="link" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][message]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['message']); ?>" />
          <p class="description"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>      
        </td>
      </tr>      
      <tr class="qlwapp-premium-field">
        <th scope="row"><?php esc_html_e('Label', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="link" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][label]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['label']); ?>" />
          <p class="description"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>      
        </td>
      </tr>      
      <tr class="qlwapp-premium-field">
        <th scope="row"><?php esc_html_e('Name', 'wp-whatsapp-chat'); ?></th>
        <td>
          <input class="qlwapp-color-field" type="link" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[scheme][name]'); ?>" value="<?php echo esc_attr($qlwapp['scheme']['name']); ?>" />
          <p class="description"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>
        </td>
      </tr>
    </tbody>
  </table>
  <?php submit_button() ?>
</form>