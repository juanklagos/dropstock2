<?php
namespace DHWC_Elementor;

class Settings extends \WC_Settings_Page{
	public function __construct(){
		$this->id = 'dhwc_elementor';
		$this->label = __('Page Templates','dhwc_elementor');
		parent::__construct();
	}
	
	public function get_sections(){
		$sections = array(
			'' 					=> __( 'Product templates', 'dhwc_elementor' ),
			'page_template' 	=> __( 'Page templates', 'dhwc_elementor' ),
		);
		
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}
	
	public function get_settings( $current_section = '' ) {
		$settings = [];
		if('page_template' === $current_section){
			$page_templates = get_posts(array(
				'post_type'=> 'dhwc_page_template',
				'posts_per_page'=>-1
			));
			$page_template_options = array();
			$page_template_options[''] = __('Select page template&hellip;','dhwc_elementor');
			foreach ((array)$page_templates as $page_template){
				$page_template_options[$page_template->ID] = $page_template->post_title;
			}
			
			$settings = [
				[
					'title' => __( 'Page Templates', 'dhwc_elementor' ),
					'type'  => 'title',
					'id'    => 'dhwc_elementor_page_template',
				],
				[
					'title'    => __( 'Account login', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_account_login',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'title'    => __( 'Cart', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_cart',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'title'    => __( 'Cart Empty', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_cart_empty',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'title'    => __( 'Checkout', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_checkout',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'title'    => __( 'Thankyou', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_checkout_thankyou',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'title'    => __( 'Checkout order receipt', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_page_template_checkout_order_receipt',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				],
				[
					'type' => 'sectionend',
					'id'   => 'dhwc_elementor_page_template',
				]
			];
			
			$settings[] = [
				'title' => __( 'Product Archive Template', 'dhwc_elementor' ),
				'type'  => 'title',
				'id'    => 'dhwc_elementor_archive_template',
			];
			
			$settings[] = [
				'title'    => __( 'Shop ', 'dhwc_elementor' ),
				'id'       => 'dhwc_elementor_page_template_shop',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width:300px;',
				'type'     => 'select',
				'default'  => '',
				'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
				'desc_tip' => true,
				'options'  => $page_template_options,
			];
			
			$product_taxonomies = dhwc_elementor_page_template_allow_taxonomies();
			
			foreach ((array) $product_taxonomies as $tax_name=>$tax_label){
				$settings[] = array(
					'title'    => $tax_label,
					'id'       => 'dhwc_elementor_page_template_'.$tax_name,
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'type'     => 'select',
					'default'  => '',
					'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
					'desc_tip' => true,
					'options'  => $page_template_options,
				);
			}
			
			$settings[] = [
				'title'    => __('Product Attribute page'),
				'id'       => 'dhwc_elementor_page_template_attribute',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width:300px;',
				'type'     => 'select',
				'default'  => '',
				'desc'     => __( 'Create page template in menu: WooCommerce &rarr; Page Templates.', 'dhwc_elementor' ),
				'desc_tip' => true,
				'options'  => $page_template_options,
			];
			
			$settings[] = [
				'type' => 'sectionend',
				'id'   => 'dhwc_elementor_archive_template',
			];
		}else{
			$template_options = [];
			
			$template_options[''] = __('Select default template&hellip;','dhwc_elementor');
			
			$templates = get_posts([
				'post_type'=> 'dhwc_template',
				'posts_per_page'=>-1
			]);
			foreach ($templates as $template){
				$template_options[$template->ID] = $template->post_title;
			}
			
			$settings = [
				[
					'title'    => __( 'Product Template', 'dhwc_elementor' ),
					'type'     => 'title',
					'id' => 'dhwc_elementor_product_template'
				],
				[
					'title'    => __( 'Default Template', 'dhwc_elementor' ),
					'desc'     => __( 'This controls what is product template default.', 'dhwc_elementor' ),
					'id'       => dhwc_elementor_get_default_template_key(),
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'select',
					'options'  => $template_options,
					'desc_tip' => true,
				],
				[
					'type' => 'sectionend',
					'id' => 'dhwc_elementor_product_template'
				]
			];
			
			$settings[] = [
				'title' => __( 'Product Types', 'dhwc_elementor' ),
				'type'  => 'title',
				'id'    => 'dhwc_elementor_product_type_template',
			];
			$product_types = dhwc_elementor_get_product_types();
			foreach ((array) $product_types as $product_type => $product_type_label){
				$settings[] = [
					'title'    => sprintf(__( 'Template for: %s', 'dhwc_elementor' ),$product_type_label),
					'desc'     => __( 'This controls what is custom template for product type.', 'dhwc_elementor' ),
					'id'       => 'dhwc_elementor_template_product_'.$product_type,
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'select',
					'options'  => $template_options,
					'desc_tip' => true,
				];
			}
			
			$settings[] = [
				'type' => 'sectionend',
				'id' => 'dhwc_elementor_product_type_template'
			];
		}
		return apply_filters( 'dhwc_elementor_settings_' . $this->id, $settings, $current_section );
	}
	
	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;
		
		$settings = $this->get_settings( $current_section );
		
		\WC_Admin_Settings::output_fields( $settings );
	}
	
	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;
		$settings = $this->get_settings($current_section);
		
		\WC_Admin_Settings::save_fields( $settings );
	}
}
return new \DHWC_Elementor\Settings;