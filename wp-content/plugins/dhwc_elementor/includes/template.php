<?php

namespace DHWC_Elementor;

class Template{
	
	private static $_instance = null;
	
	public function init(){
		add_action( 'template_redirect', array( $this, 'register_single_product_template' ) );
			
		add_action( 'template_redirect', array(  $this, 'register_assets' ) );
		add_action(	'wp_enqueue_scripts', array( $this, 'frontend_assets'), 300);
			
		add_action( 'admin_bar_menu', array( $this,'admin_bar_menu'), 1000 );
		
		if(!is_admin() && !isset($_REQUEST['elementor-preview']) ){
			add_filter(	'wc_get_template_part', array( $this,'wc_get_template_part'),50,3);
		}
	
		if(apply_filters('dhwc_elementor_use_custom_single_product_template',false)){
			add_filter( 'template_include', array( $this, 'template_loader' ),50 );
		}
		//Archive Tempalte
		add_filter( 'template_include', [ $this, 'template_include' ],999 );
		
		//Cart, Checkout and Account Login Template
		add_filter( 'wc_get_template', array( $this, 'filter_wc_template' ), 999999, 3 );
	}
	
	public function filter_wc_template($template, $template_name, $args){
		switch ($template_name){
			case 'cart/cart.php':
				if($cart_template = get_post( dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_cart'))){
					$template = dhwc_elementor_get_template('cart/cart.php');
				}
			break;
			case 'cart/cart-empty.php':
				if($cart_empty_template = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_cart_empty'))){
					$template = dhwc_elementor_get_template('cart/cart-empty.php');
				}
			break;
			case 'checkout/form-checkout.php':
				if($checkout_template = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_checkout' ))){
					$template = dhwc_elementor_get_template('checkout/form-checkout.php');
				}
			break;
			case 'checkout/thankyou.php':
				if($thankyou_template = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_checkout_thankyou'))){
					$template = dhwc_elementor_get_template('checkout/thankyou.php');
				}
			break;
			case 'checkout/order-receipt.php':
				if($order_receipt_page_template = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_checkout_order_receipt'))){
					$template = dhwc_elementor_get_template('checkout/order-receipt.php');
				}
			break;
			case 'myaccount/form-login.php':
				if( $account_login_page = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_account_login'))){
					$template = dhwc_elementor_get_template('myaccount/form-login.php');
				}
			break;
		}
		return $template;
	}
	
	public function template_include($template){
		global $dhwc_elementor_product_archive_content_template;
		
		$archive_template = false;
		
		if((is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) )) && $shop_page = get_post(wc_get_page_id( 'shop' )) && ($shop_template = get_post(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_shop')))){
			$archive_template = $shop_template;
		}elseif (is_product_taxonomy()){
			$term = get_queried_object();
			$taxonomy = $term->taxonomy;
			$content_template_id = get_term_meta( $term->term_id, '_dhwc_elementor_term_content_template', true );
			
			if(!empty($content_template_id) && $taxonomy_template = get_post($content_template_id)){
				$archive_template = $taxonomy_template;
			}else{
				$product_taxonomies = dhwc_elementor_page_template_allow_taxonomies();
				$taxonomy_template_id = 0;
				if(array_key_exists($taxonomy, $product_taxonomies)){
					$taxonomy_template_id =  dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_'.$taxonomy);
				}else if(strstr($taxonomy,'pa_')){
					$taxonomy_template_id =  dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_attribute');
				}
				if($taxonomy_template_id && $taxonomy_template = get_post($taxonomy_template_id)){
					$archive_template = $taxonomy_template;
				}
			}
		}
		if(!empty($archive_template)){
			$dhwc_elementor_product_archive_content_template = $archive_template;
			return dhwc_elementor_get_template('archive-product.php');
		}
		return $template;
	}
	
	public function admin_bar_menu($wp_admin_bar){
		if ( ! is_object( $wp_admin_bar ) ) {
			global $wp_admin_bar;
		}
		$product_template_id = $this->get_register_single_product_template();
		if ( is_product() && !empty($product_template_id) && ($product_template_document = \Elementor\Plugin::$instance->documents->get($product_template_id)) ) {
			$wp_admin_bar->add_menu( array(
				'id' => 'edit-product-template',
				'title' => __( 'Edit Product Template', 'dhwc_elementor' ),
				'href' => $product_template_document->get_edit_url(),
				'meta' => array( 'target' => '_blank' ),
			) );
		}
		if(is_cart() && $cart_template_id = (int) get_option('dhwc_elementor_page_template_cart')) {
			if($cart_template_document = \Elementor\Plugin::$instance->documents->get($cart_template_id)){
				$wp_admin_bar->add_menu( array(
					'id' => 'edit-cart-template',
					'title' => __( 'Edit Cart Template', 'dhwc_elementor' ),
					'href' => $cart_template_document->get_edit_url(),
					'meta' => array( 'target' => '_blank' ),
				) );
			}
		}
		if(is_checkout() && $checkout_template_id = (int)  get_option('dhwc_elementor_page_template_checkout' )) {
			if($checkout_template_document = \Elementor\Plugin::$instance->documents->get($checkout_template_id)){
				$wp_admin_bar->add_menu( array(
					'id' => 'edit-checkout-template',
					'title' => __( 'Edit Checkout Template', 'dhwc_elementor' ),
					'href' => $checkout_template_document->get_edit_url(),
					'meta' => array( 'target' => '_blank' ),
				) );
			}
		}
	}
	
	public function register_single_product_template(){
		global $post,$dhwc_elementor_registed_single_product_template_id;
		if(!is_product()){
			return;
		}
		if(empty($dhwc_elementor_registed_single_product_template_id)){
			$dhwc_elementor_registed_single_product_template_id = dhwc_elementor_get_custom_template($post);
			do_action('dhwc_elementor_register_single_product_template',$dhwc_elementor_registed_single_product_template_id);
		}
		return $dhwc_elementor_registed_single_product_template_id;
	}
	
	public function get_register_single_product_template(){
		global $dhwc_elementor_registed_single_product_template_id;
		return $dhwc_elementor_registed_single_product_template_id;
	}
	
	public function register_assets(){
		wp_register_style('dhwc_elementor-font-awesome', DHWC_ELEMENTOR_URL.'/assets/fonts/awesome/css/font-awesome.min.css',array(),'4.0.3');
		wp_register_style('dhwc_elementor', DHWC_ELEMENTOR_URL.'/assets/css/style.css',array(),DHWC_ELEMENTOR_VERSION);
		wp_register_script( 'slick',DHWC_ELEMENTOR_URL.'/assets/js/slick/slick.min.js', ['jquery'], DHWC_ELEMENTOR_VERSION,	true);
	}
	
	public function frontend_assets(){
		wp_enqueue_style('dhwc_elementor-font-awesome');
		wp_enqueue_style('dhwc_elementor');
	}
	
	public function wc_get_template_part($template, $slug, $name){
		global $post, $dhwc_elementor_single_product_template;
		$dhwc_elementor_product_template_id = $this->get_register_single_product_template();
		if(!empty($dhwc_elementor_product_template_id) && $slug === 'content' && $name === apply_filters('dhwc_elementor_single_product_template_name', 'single-product')){
			
			do_action('dhwc_elementor_before_override');

			if($dhwc_elementor_single_product_template = get_post( $dhwc_elementor_product_template_id)){
				
				return dhwc_elementor_get_template('content-single-product.php');
			}
			
			do_action('dhwc_elementor_after_override');
		}
		return $template;
	}
	
	public function template_loader($template){
		if ( is_singular('product') && $dhwc_elementor_product_template = get_post($this->get_register_single_product_template())) {
			return dhwc_elementor_get_template('single-product.php');
		}
		return $template;
	}
	
	/**
	 * 
	 * @return \DHWC_Elementor\Template
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
\DHWC_Elementor\Template::get_instance()->init();