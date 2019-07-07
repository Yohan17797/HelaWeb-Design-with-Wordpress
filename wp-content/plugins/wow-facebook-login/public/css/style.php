<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
.wow-fb-login {
	font-size:<?php if (!empty($param['fb_text_size'])) { echo $param['fb_text_size'];} else {echo '18';} ?>px;
	padding: <?php if (!empty($param['fb_button_padding_top'])) { echo $param['fb_button_padding_top']; } else {echo '5';} ?>px <?php if (!empty($param['fb_button_padding_right'])) { echo $param['fb_button_padding_right']; } else {echo '20';} ?>px <?php if (!empty($param['fb_button_padding_bottom'])) { echo $param['fb_button_padding_bottom'];} else {echo '5';}  ?>px <?php if (!empty($param['fb_button_padding_left'])) { echo $param['fb_button_padding_left'];} else {echo '20';} ?>px;
	border: <?php if (!empty($param['fb_button_border'])) { echo $param['fb_button_border'];} {echo '0';} ?>px solid <?php if (!empty($param['fb_color_border'])) { echo $param['fb_color_border'];} else {echo '0';} ?>;
	border-radius: <?php if (!empty($param['fb_button_border_radius'])) { echo $param['fb_button_border_radius']; } else {echo '0';} ?>px;
	background: <?php if (!empty($param['fb_background'])) { echo $param['fb_background']; } else {echo '#3b5998';} ?>;
	color: <?php if (!empty($param['fb_color_text'])) { echo  $param['fb_color_text']; } else {echo '#ffffff';} ?>;
}
<?php
	$param['fb_icon_show'] = !empty($param['fb_icon_show']) ? $param['fb_icon_show'] : 'before';
if ($param['fb_icon_show'] != 'none'){ ?>
.wow-fb-login:<?php echo $param['fb_icon_show']; ?> {
   font-family: "FontAwesome";
    content: "\f09a";    
	font-size: <?php if(!empty($param['fb_text_icon'])) { echo $param['fb_text_icon'];} else {echo '18';} ?>px;
	color: <?php if(!empty($param['fb_color_icon'])){ echo $param['fb_color_icon']; } else {echo '#ffffff';} ?>;
	<?php 
	$param['fb_margin_icon'] = !empty($param['fb_margin_icon']) ? $param['fb_margin_icon'] : '5';
		if ($param['fb_icon_show'] == 'before'){ $margin = 'margin-right:';} else {$margin = 'margin-left:';} echo $margin.' '.$param['fb_margin_icon'].'px;'; ?>
}
<?php } ?>
.wow-fb-login:hover {	
	background: <?php if (!empty($param['fb_background_hover'])) { echo $param['fb_background_hover']; } else {echo '#8b9dc3';} ?>;
	color: <?php if (!empty($param['fb_color_text'])) { echo $param['fb_color_text']; } else {echo '#ffffff';} ?>;
}