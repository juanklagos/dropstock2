<?php
namespace DHWC_Elementor\Widgets;

class Widget_Archive_Products extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_archive_products';
	}
	
	public function get_title() {
		return __( 'WC Archive Products', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_archive');
	}
	
	public function get_icon(){
		return array('dhwc_archive');
	}
	
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render(){
		if(\Elementor\Plugin::$instance->editor->is_edit_mode() || is_preview()){
			echo \WC_Shortcodes::products([
				'limit' => 12,
				'paginate' => true
			]);
			return;
		}
		if ( woocommerce_product_loop() ) {
			
			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
			
			woocommerce_product_loop_start();
			
			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					
					/**
					 * Hook: woocommerce_shop_loop.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'woocommerce_shop_loop' );
					
					wc_get_template_part( 'content', 'product' );
				}
			}
			
			woocommerce_product_loop_end();
			
			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
		}
		
	}
}