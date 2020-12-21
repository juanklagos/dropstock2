<?php
/*
 * Plugin Name: DHWC Elementor
 * Plugin URI: http://sitesao.com/
 * Description: WooCommerce's page builder with Elementor
 * Version: 1.2.5
 * Author: Sitesao team
 * Author URI: http://sitesao.com/
 * License: License GNU General Public License version 2 or later;
 * Copyright 2020 Sitesao team
 */
if(!defined('DHWC_ELEMENTOR_VERSION')){
	define('DHWC_ELEMENTOR_VERSION','1.2.5');
}

if(!defined('DHWC_ELEMENTOR_URL')){
	define('DHWC_ELEMENTOR_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));
}

if(!defined('DHWC_ELEMENTOR_DIR')){
	define('DHWC_ELEMENTOR_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));
}

if(!defined('DHWC_ELEMENTOR_WIDGETS_DIR')){
	define('DHWC_ELEMENTOR_WIDGETS_DIR', DHWC_ELEMENTOR_DIR.'/includes/widgets');
}

if(!class_exists('\DHWC_Elementor\Plugin')){
	require_once DHWC_ELEMENTOR_DIR.'/includes/plugin.php';
	
	/**
	 *
	 * @return \DHWC_Elementor\Plugin
	 */
	function DHWC_Elementor(){
		return \DHWC_Elementor\Plugin::get_instance();
	}
	DHWC_Elementor()->init();
}