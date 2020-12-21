<?php
namespace DHWC_Elementor;

class Editor {
	private static $_instance = null;
	
	/**
	 * @return \DHWC_Elementor\Editor
	 */
	public static function get_instance() {
		if ( is_null(self::$_instance) ){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function init(){
		add_action( 'init', array( $this, 'add_post_type_support' ) );
		add_action( 'elementor/init', array( $this, 'add_elementor_category' ) );
		
		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
			add_action( 'init', [ $this, 'wc_frontend_includes' ], 5 );
		}
		
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );
		
		add_filter('elementor/document/urls/wp_preview', array($this,'wp_preview_url'),10,2);
		add_filter('elementor/document/urls/preview', array($this,'preview_url_document'),10,2);
		
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
		add_action('elementor/controls/controls_registered', array($this, 'controls_registered'));
	
		add_action( 'elementor/editor/after_enqueue_scripts',array( $this, 'editor_enqueue_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_styles',array( $this, 'editor_enqueue_styles' ) );
		
		add_action( 'elementor/frontend/after_enqueue_scripts',array( $this, 'fontend_enqueue_scripts' ) );
	
		add_filter(	'wc_get_template_part', array( $this,'wc_get_template_part'),50,3);
	}
	
	public function wc_frontend_includes() {
		WC()->frontend_includes();
	}
	
	
	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		
		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}
	
	/**
	 *
	 * @param string $preview_url
	 * @param \Elementor\Core\Base\Document $document
	 */
	public function preview_url_document($preview_url,$document){
		$post_id = $document->get_main_id();
		if('dhwc_template'==get_post_type($post_id)){
			if($product_id = dhwc_elementor_find_product_by_template($post_id)){
				
				add_filter( 'pre_option_permalink_structure', '__return_empty_string' );
				
				$new_preview_url = set_url_scheme( add_query_arg( array(
					'elementor-preview' => $product_id,
					'dhwc_elementor_editor'=> $document->get_main_id(),
					'ver' => time(),
				) , get_permalink($product_id) ) );
				
				remove_filter( 'pre_option_permalink_structure', '__return_empty_string' );
				
				return $new_preview_url;
			}
		}
		return $preview_url;
	}
	
	/**
	 *
	 * @param string $wp_preview_url
	 * @param \Elementor\Core\Base\Document $document
	 */
	public function wp_preview_url($wp_preview_url, $document){
		$post_id = $document->get_main_id();
		if('dhwc_template'==get_post_type($post_id)){
			if($product_id = dhwc_elementor_find_product_by_template($post_id)){
				$wp_preview_url = get_preview_post_link(
					$product_id,
					[
						'preview_id' => $product_id,
						'preview_nonce' => wp_create_nonce( 'post_preview_' . $product_id ),
					]
				);
			}
		}elseif($post_id == get_option('dhwc_elementor_page_template_checkout' )){
			$wp_preview_url = wc_get_checkout_url();
		}elseif($post_id == get_option('dhwc_elementor_page_template_cart')){
			$wp_preview_url = wc_get_cart_url();
		}
		return $wp_preview_url;
	}
	
	public function add_elementor_category(){
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc_single_product', array(
			'title' => __( 'WC Single Product', 'dhwc_elementor' ),
		), 1 );
		
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc_cart', array(
			'title' => __( 'WC Cart', 'dhwc_elementor' ),
		), 2 );
		
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc_checkout', array(
			'title' => __( 'WC Checkout', 'dhwc_elementor' ),
		), 3 );
		
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc_account', array(
			'title' => __( 'WC My Account', 'dhwc_elementor' ),
		), 4 );
		
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc_archive', array(
			'title' => __( 'WC Archive', 'dhwc_elementor' ),
		), 5 );
	
		\Elementor\Plugin::$instance->elements_manager->add_category( 'dhwc', array(
			'title' => __( 'WooCommerce', 'dhwc_elementor' ),
		), 5 );
	}
	
	
	public function add_post_type_support(){
		add_post_type_support( 'dhwc_template', 'elementor' );
		add_post_type_support( 'dhwc_page_template', 'elementor' );
		add_post_type_support( 'product', 'elementor' );
	}
	
	public function wc_get_template_part($template, $slug, $name){
		if(isset($_REQUEST['dhwc_elementor_editor']) && $slug === 'content' && $name === apply_filters('dhwc_elementor_single_product_template_name', 'single-product')){
			$file = 'content-single-product.php';
			$find[] = 'dhwc_elementor/editor/' . $file;
			$template       = locate_template( $find );
			if ( ! $template ){
				$template = DHWC_ELEMENTOR_DIR . '/includes/editor/' . $file;
			}
			return $template;
		}
		return $template;
	}
	
	public function editor_enqueue_styles(){
		wp_enqueue_style('dhwc_elementor_editor', DHWC_ELEMENTOR_URL.'/assets/css/elementor-editor.css',array(),DHWC_ELEMENTOR_VERSION);
	}
	
	public function editor_enqueue_scripts(){
		wp_enqueue_script('dhwc_elementor_editor',DHWC_ELEMENTOR_URL.'/assets/js/elementor-editor.js',array('elementor-common','elementor-editor-modules','elementor-editor-document'),DHWC_ELEMENTOR_VERSION,true);
	
		wp_localize_script( 'dhwc_elementor_editor', 'dhwc_elementor_editor_params', array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' 		=> wp_create_nonce( 'dhwc_elementor_editor' ),
		));
	}
	
	public function fontend_enqueue_scripts(){
		if(!\Elementor\Plugin::$instance->preview->is_preview_mode()){
			return;
		}
		wp_enqueue_script('dhwc_elementor_frontend',DHWC_ELEMENTOR_URL.'/assets/js/elementor-frontend.js',array('elementor-frontend-modules'),DHWC_ELEMENTOR_VERSION,true);
	
		if(isset($_REQUEST['dhwc_elementor_editor'])){
			wp_enqueue_script('dhwc-elementor-product-image',DHWC_ELEMENTOR_URL.'/assets/js/product-image.min.js', ['slick'], DHWC_ELEMENTOR_VERSION, true);
			
			wp_enqueue_script( 'zoom', plugins_url( 'assets/js/zoom/jquery.zoom.min.js', WC_PLUGIN_FILE ),array( 'jquery' ),'1.7.21',true);
			wp_register_script( 'photoswipe', plugins_url( 'assets/js/photoswipe/photoswipe.min.js', WC_PLUGIN_FILE ),array(),'4.1.1',true);
			wp_enqueue_script( 'photoswipe-ui-default', plugins_url( 'assets/js/photoswipe/photoswipe-ui-default.min.js', WC_PLUGIN_FILE ),array( 'photoswipe' ),'4.1.1',true);
			
			wp_register_style( 'photoswipe', plugins_url( 'assets/css/photoswipe/photoswipe.css', WC_PLUGIN_FILE ),array(),'4.1.1');
			wp_enqueue_style( 'photoswipe-default-skin', plugins_url( 'assets/css/photoswipe/default-skin/default-skin.css', WC_PLUGIN_FILE ),array( 'photoswipe' ),'4.1.1');
			add_action( 'wp_footer', function(){
				wc_get_template( 'single-product/photoswipe.php' );
			},100);
		}
		
	}
	
	/**
	 *
	 * @param \Elementor\Controls_Manager $controls_manager
	 */
	public function controls_registered($controls_manager){
		require_once DHWC_ELEMENTOR_DIR.'/includes/controls/ajaxselect2.php';
		$controls_manager->register_control('dhwc_elementor_ajaxselect2',new \DHWC_Elementor\Controls\Control_Ajaxselect2());
	}
	
	/**
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 */
	public function widgets_registered($widgets_manager) {
	
		// We check if the Elementor plugin has been installed / activated.
		if(defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')){
			$widgets = [
				'dhwc_elementor_product_category',
				'dhwc_elementor_product_categories',
				'dhwc_elementor_products',
				'dhwc_elementor_recent_products',
				'dhwc_elementor_sale_products',
				'dhwc_elementor_best_selling_products',
				'dhwc_elementor_top_rated_products',
				'dhwc_elementor_featured_products',
				'dhwc_elementor_product_attribute',
				'dhwc_elementor_shop_messages',
				'dhwc_elementor_order_tracking',
				'dhwc_elementor_cart',
				'dhwc_elementor_checkout',
				'dhwc_elementor_my_account',
				'dhwc_elementor_breadcrumb',
			];
			foreach ($widgets as $widget){
				$file_name = str_replace('dhwc_elementor_', '', $widget);
				require_once DHWC_ELEMENTOR_WIDGETS_DIR."/{$file_name}.php";
				$class_name = '\DHWC_Elementor\Widgets\Widget_'.$file_name;
				if(class_exists($class_name)){
					$widgets_manager->register_widget_type( new $class_name );
				}
			}
			$this->_register_account_widgets($widgets_manager);
			$this->_register_archive_widgets($widgets_manager);
			$this->_register_single_product_widgets($widgets_manager);
			$this->_register_cart_widgets($widgets_manager);
			$this->_register_cart_empty_widgets($widgets_manager);
			$this->_register_checkout_widgets($widgets_manager);
			$this->_register_checkout_order_receipt_widgets($widgets_manager);
			$this->_register_thankyou_widgets($widgets_manager);
		}
	}
	
	protected function _register_account_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_account_login')){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/account_login.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/account_register.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Account_Login );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Account_Register );
	}
	
	private function _is_templated($post_id = null){
		$is_templated = false;
		if(empty($post_id)){
			return $is_templated;
		}
		$settings = [
			'dhwc_elementor_page_template_account_login',
			'dhwc_elementor_page_template_cart',
			'dhwc_elementor_page_template_cart_empty',
			'dhwc_elementor_page_template_checkout',
			'dhwc_elementor_page_template_checkout_thankyou',
			'dhwc_elementor_page_template_checkout_order_receipt'
		];
		foreach ($settings as $setting){
			if($post_id == absint(get_option($setting))){
				$is_templated = true;
				break;
			}
		}
		return $is_templated;
	}
	
	protected function _register_archive_widgets($widgets_manager){
		
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if(get_post_type($post_id) !== 'dhwc_page_template' || $this->_is_templated($post_id)){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/archive_title.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/archive_description.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/archive_products.php';
		$widgets_manager->register_widget_type(new \DHWC_Elementor\Widgets\Widget_Archive_Title);
		$widgets_manager->register_widget_type(new \DHWC_Elementor\Widgets\Widget_Archive_Description);
		$widgets_manager->register_widget_type(new \DHWC_Elementor\Widgets\Widget_Archive_Products);
	}
	
	protected function _register_thankyou_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_checkout_thankyou')){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/thankyou_order_details.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/thankyou_order_overview.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/thankyou_message.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Thankyou_Message);
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Thankyou_Order_Details );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Thankyou_Order_Overview );
	}
	
	protected function _register_checkout_order_receipt_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_checkout_order_receipt')){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_order_receipt.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Order_Receipt );
	}
	
	protected function _register_checkout_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_checkout' )){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_billing.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_shipping.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_order_items.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_coupon.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/checkout_payment.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Billing );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Shipping );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Order_Items );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Coupon );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Checkout_Payment );
	}
	
	protected function _register_cart_empty_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_cart_empty')){
				return;
			}
		}
		
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/cart_empty.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Cart_Empty );
	}
	
	protected function _register_cart_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			if($post_id != get_option('dhwc_elementor_page_template_cart' )){
				return;
			}
		}
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/cart_coupon.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/cart_cross_sells.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/cart_items.php';
		require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/cart_totals.php';
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Cart_Items );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Cart_Coupon );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Cart_Totals );
		$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Cart_Cross_Sells );
	}
	
	protected function _register_single_product_widgets($widgets_manager){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$document = \Elementor\Plugin::$instance->documents->get_current();
			if(empty($document)){
				return;
			}
			$post_id = $document->get_main_id();
			
			if(get_post_type($post_id) != 'dhwc_template'){
				return;
			}
		}
		
		require_once DHWC_ELEMENTOR_DIR .'/includes/widgets/single_product_base.php';
		
		$widgets = [
			'dhwc_elementor_single_product_images',
			'dhwc_elementor_single_product_title',
			'dhwc_elementor_single_product_rating',
			'dhwc_elementor_single_product_price',
			'dhwc_elementor_single_product_excerpt'	,
			'dhwc_elementor_single_product_description',
			'dhwc_elementor_single_product_additional_information',
			'dhwc_elementor_single_product_add_to_cart',
			'dhwc_elementor_single_product_meta',
			'dhwc_elementor_single_product_sharing',
			'dhwc_elementor_single_product_data_tabs',
			'dhwc_elementor_single_product_reviews'	,
			'dhwc_elementor_single_product_upsell_products',
			'dhwc_elementor_single_product_related_products',
			'dhwc_elementor_single_product_wishlist',
			'dhwc_elementor_single_product_custom_field',
		];
		
		foreach ($widgets as $widget){
			$file_name = str_replace('dhwc_elementor_', '', $widget);
			require_once DHWC_ELEMENTOR_WIDGETS_DIR."/{$file_name}.php";
			$class_name = '\DHWC_Elementor\Widgets\Widget_'.$file_name;
			$widgets_manager->register_widget_type( new $class_name );
		}
		
		if(function_exists('fpd_get_option')){
			require_once DHWC_ELEMENTOR_WIDGETS_DIR.'/single_product_fpd.php';
			$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Single_Product_FPD() );
		}
		
		
		if (class_exists ( 'acf' )) {
			require_once  DHWC_ELEMENTOR_WIDGETS_DIR.'/single_product_acf_field.php';
			$widgets_manager->register_widget_type( new \DHWC_Elementor\Widgets\Widget_Single_Product_Acf_Field() );
		}
	}
}
\DHWC_Elementor\Editor::get_instance()->init();