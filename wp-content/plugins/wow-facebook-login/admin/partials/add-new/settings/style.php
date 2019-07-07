<?php
	/**
		* Facebook button style
		*
		* @package     
		* @subpackage  Settings
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	
	
	$fb_button_padding_top = array(
	'id'   => 'fb_button_padding_top',
	'name' => 'fb_button_padding_top',	
	'type' => 'text',
	'val' => isset($param['fb_button_padding_top']) ? $param['fb_button_padding_top'] : '5',	
	);
	
	$fb_button_padding_right = array(
	'id'   => 'fb_button_padding_right',
	'name' => 'fb_button_padding_right',	
	'type' => 'text',
	'val' => isset($param['fb_button_padding_right']) ? $param['fb_button_padding_right'] : '20',	
	);
	
	$fb_button_padding_bottom = array(
	'id'   => 'fb_button_padding_bottom',
	'name' => 'fb_button_padding_bottom',	
	'type' => 'text',
	'val' => isset($param['fb_button_padding_bottom']) ? $param['fb_button_padding_bottom'] : '5',	
	);
	
	$fb_button_padding_left = array(
	'id'   => 'fb_button_padding_left',
	'name' => 'fb_button_padding_left',	
	'type' => 'text',
	'val' => isset($param['fb_button_padding_left']) ? $param['fb_button_padding_left'] : '20',	
	);
	
	$fb_button_border = array(
	'id'   => 'fb_button_border',
	'name' => 'fb_button_border',	
	'type' => 'text',
	'val' => isset($param['fb_button_border']) ? $param['fb_button_border'] : '0',	
	);
	
	$fb_button_border_radius = array(
	'id'   => 'fb_button_border_radius',
	'name' => 'fb_button_border_radius',	
	'type' => 'text',
	'val' => isset($param['fb_button_border_radius']) ? $param['fb_button_border_radius'] : '0',	
	);
	
	$fb_text_size = array(
	'id'   => 'fb_text_size',
	'name' => 'fb_text_size',	
	'type' => 'text',
	'val' => isset($param['fb_text_size']) ? $param['fb_text_size'] : '18',	
	);
	
	$fb_text_icon = array(
	'id'   => 'fb_text_icon',
	'name' => 'fb_text_icon',	
	'type' => 'text',
	'val' => isset($param['fb_text_icon']) ? $param['fb_text_icon'] : '18',	
	);
	
	$fb_icon_show = array(
	'id'   => 'fb_icon_show',
	'name' => 'fb_icon_show',	
	'type' => 'select',
	'val' => isset($param['fb_icon_show']) ? $param['fb_icon_show'] : 'before',	
	'option' => array(
	'before' => 'Before text',
	'after' => 'After text',
	'none' => 'none',
	),
	);
	
	$fb_margin_icon = array(
	'id'   => 'fb_margin_icon',
	'name' => 'fb_margin_icon',	
	'type' => 'text',
	'val' => isset($param['fb_margin_icon']) ? $param['fb_margin_icon'] : '5',	
	);
	
	$fb_background = array(
	'id'   => 'fb_background',
	'name' => 'fb_background',	
	'type' => 'color',
	'val' => isset($param['fb_background']) ? $param['fb_background'] : '#3b5998',	
	);
	
	$fb_background_hover = array(
	'id'   => 'fb_background_hover',
	'name' => 'fb_background_hover',	
	'type' => 'color',
	'val' => isset($param['fb_background_hover']) ? $param['fb_background_hover'] : '#8b9dc3',	
	);
	
	$fb_color_text = array(
	'id'   => 'fb_color_text',
	'name' => 'fb_color_text',	
	'type' => 'color',
	'val' => isset($param['fb_color_text']) ? $param['fb_color_text'] : '#ffffff',	
	);
	
	$fb_color_icon = array(
	'id'   => 'fb_color_icon',
	'name' => 'fb_color_icon',	
	'type' => 'color',
	'val' => isset($param['fb_color_icon']) ? $param['fb_color_icon'] : '#ffffff',	
	);
	
	$fb_color_border = array(
	'id'   => 'fb_color_border',
	'name' => 'fb_color_border',	
	'type' => 'color',
	'val' => isset($param['fb_color_border']) ? $param['fb_color_border'] : '#ffffff',	
	);
?>