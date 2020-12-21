<?php
namespace DHWC_Elementor;

class Plugin {
	/**
	 * 
	 * @var \WC_Order|null;
	 */
	public $current_order;
	private static $_instance = null;
	
	public function __construct(){
		add_action( 'plugins_loaded', array($this,'plugins_loaded'));
	}
	
	public function plugins_loaded(){
		
		if(!function_exists('is_plugin_active')){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
		}
		
		if ( is_plugin_active( 'woocommerce/woocommerce.php' )) {
			if(defined('ELEMENTOR_PATH')){
				require_once DHWC_ELEMENTOR_DIR.'/includes/editor.php';
			}else{
				add_action('admin_notices', array($this,'notice'));
				return;
			}
		}else{
			add_action('admin_notices', array($this,'woocommerce_notice'));
			return ;
		}
		
		load_plugin_textdomain( 'dhwc_elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		
		require_once DHWC_ELEMENTOR_DIR.'/includes/functions.php';
		
		require_once DHWC_ELEMENTOR_DIR.'/includes/post-types.php';
		
		if(is_admin()):
			require_once DHWC_ELEMENTOR_DIR.'/includes/admin.php';
		else:
			require_once DHWC_ELEMENTOR_DIR.'/includes/template.php';
		endif;
		
		add_action('init',array($this,'init'),2);
	}
	
	public function init(){
		add_filter('option__dhwc_elementor_default_template', array($this, 'translate_pages_in_settings'));
	}
	
	public function translate_pages_in_settings($value){
		global $pagenow;
		if( $pagenow == 'options-permalink.php' || ( isset( $_GET['page'] ) && $_GET['page'] == 'wpml-wcml' ) ){
			return $value;
		}
		
		return apply_filters( 'translate_object_id',$value, 'page', true);
	}
	
	public function notice(){
		$plugin = get_plugin_data(__FILE__);
		echo '<div class="updated"><p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="https://elementor.com/" target="_blank">Elementor Page Builder</a></strong> plugin to be installed and activated on your site.', 'dhwc_elementor'), $plugin['Name']) . '</p> </div>';
	}
	
	public function woocommerce_notice(){
		$plugin = get_plugin_data(__FILE__);
		echo '<div class="updated"><p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a></strong> plugin to be installed and activated on your site.', 'dhwc_elementor'), $plugin['Name']) . '</p></div>';
	}

	/**
	 * 
	 * @return \DHWC_Elementor\Plugin
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}