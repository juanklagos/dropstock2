<?php
namespace DHWC_Elementor\Widgets;

class Widget_Cart_Empty extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_cart_empty';
	}
	
	public function get_title() {
		return __( 'WC Cart Empty', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_cart');
	}
	
	public function get_icon(){
		return array('dhwc_cart');
	}
	
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render() {
		if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<p class="return-to-shop">
				<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'Return to shop', 'woocommerce' ); ?>
				</a>
			</p>
		<?php 
		endif;
	}
}