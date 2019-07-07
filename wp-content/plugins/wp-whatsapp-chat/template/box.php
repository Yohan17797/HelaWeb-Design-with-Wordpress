<div id="qlwapp" class="qlwapp-free <?php printf("qlwapp-%s qlwapp-%s qlwapp-%s qlwapp-%s", esc_attr($qlwapp['button']['layout']), esc_attr($qlwapp['button']['position']), esc_attr($qlwapp['display']['devices']), esc_attr($qlwapp['button']['rounded'] === 'yes' ? 'rounded' : 'square')); ?>">
  <div class="qlwapp-container">
    <?php if ($qlwapp['box']['enable'] === 'yes'): ?>
      <div class="qlwapp-box">
        <?php if (!empty($qlwapp['box']['header'])): ?>
          <div class="qlwapp-header">
            <i class="qlwapp-close" data-action="close">&times;</i>
            <div class="qlwapp-description">
              <?php echo wpautop(wp_kses_post(wpautop($qlwapp['box']['header']))); ?>
            </div>
          </div>
        <?php endif; ?>
        <div class="qlwapp-body">
          <?php if (isset($qlwapp['contacts'][0])): ?>
            <a class="qlwapp-account" data-action="open" data-phone="<?php echo esc_attr($qlwapp['contacts'][0]['phone']); ?>" data-message="<?php echo esc_html($qlwapp['user']['message']); ?>" href="javascript:void(0);" target="_blank">
              <?php if (!empty($qlwapp['contacts'][0]['avatar'])): ?>
                <div class="qlwapp-avatar">
                  <div class="qlwapp-avatar-container">
                    <img alt="<?php printf("%s %s", esc_html($qlwapp['contacts'][0]['firstname']), esc_html($qlwapp['contacts'][0]['lastname'])); ?>" src="<?php echo esc_url($qlwapp['contacts'][0]['avatar']); ?>">
                  </div>
                </div>
              <?php endif; ?>
              <div class="qlwapp-info">
                <span class="qlwapp-label"><?php echo esc_html($qlwapp['contacts'][0]['label']); ?></span>
                <span class="qlwapp-name"><?php printf("%s %s", esc_html($qlwapp['contacts'][0]['firstname']), esc_html($qlwapp['contacts'][0]['lastname'])); ?></span>
              </div>
            </a>
          <?php endif; ?>
        </div>
        <?php if (!empty($qlwapp['box']['footer'])): ?>
          <div class="qlwapp-footer">
            <?php echo wpautop(wp_kses_post($qlwapp['box']['footer'])); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <a class="qlwapp-toggle" data-action="<?php echo ($qlwapp['box']['enable'] === 'yes' ? 'box' : 'open'); ?>" data-phone="<?php echo esc_attr($qlwapp['button']['phone']); ?>" data-message="<?php echo esc_html($qlwapp['user']['message']); ?>" href="#" target="_blank">
      <?php if ($qlwapp['button']['icon']): ?>
        <i class="qlwapp-icon <?php echo esc_attr($qlwapp['button']['icon']); ?>"></i>
      <?php endif; ?>
      <i class="qlwapp-close" data-action="close">&times;</i>
      <?php if ($qlwapp['button']['text']): ?>
        <span class="qlwapp-text"><?php echo esc_html($qlwapp['button']['text']); ?></span>
      <?php endif; ?>
    </a>
    <?php if ($qlwapp['button']['developer'] === 'yes'): ?>
      <a class="qlwapp-developer" href="<?php echo esc_url(QLWAPP_DEMO_URL); ?>" target="_blank"><?php esc_html_e('Powered by QuadLayers'); ?></a>
    <?php endif; ?>
  </div>
</div>
