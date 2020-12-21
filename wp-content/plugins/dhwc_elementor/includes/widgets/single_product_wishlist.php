<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Wishlist extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_wishlist';
	}
	
	public function get_title() {
		return __( 'WC Single Product Wishlist', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render() {
		if (defined ( 'YITH_WCWL' )) {
			$content = '['.$this->get_name().']';
			?>
			<div class="dhwc-elementor-product-wishlist">
				<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]')?>
			</div>
			<?php 
		}else{
			echo 'Please install and activate plugin <a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/">YITH WooCommerce Wishlist</a> on your site';
		}
		
	}
	
}