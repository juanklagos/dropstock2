<?php
namespace DHWC_Elementor\Widgets;

class Widget_Checkout_Order_Receipt extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_checkout_order_receipt';
	}
	
	public function get_title() {
		return __( 'WC Checkout Order Receipt', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_checkout');
	}
	
	public function get_icon(){
		return array('dhwc_checkout');
	}
	
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render() {
	
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$order = dhwc_elementor_get_last_order();
			if(empty($order)){
				echo __('Order Receipt Placeholder','dhwc_elementor');
				return;
			}
		}else{
			$order = DHWC_Elementor()->current_order;
		}
		if(!empty($order)){
			?>
			<ul class="order_details">
				<li class="order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
				</li>
				<li class="date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
				</li>
				<li class="total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
				</li>
				<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="method">
					<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</li>
				<?php endif; ?>
			</ul>
			
			<?php do_action( 'woocommerce_receipt_' . $order->get_payment_method(), $order->get_id() ); ?>
			
			<div class="clear"></div>
						
			<?php 
		}
	}
}