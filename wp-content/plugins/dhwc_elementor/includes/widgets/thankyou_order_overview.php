<?php
namespace DHWC_Elementor\Widgets;

class Widget_Thankyou_Order_Overview extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_thankyou_order_overview';
	}
	
	public function get_title() {
		return __( 'WC Thankyou Order Overview', 'dhwc_elementor' );
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
			if(!$order){
				echo __('Thankyou Order Overview Placeholder','dhwc_elementor');
				return;
			}
		}else{
			$order = DHWC_Elementor()->current_order;
		}
		
		if(!$order || $order->has_status('failed')){
			return;
		}
		
		?>
		<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

			<li class="woocommerce-order-overview__order order">
				<?php _e( 'Order number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>

			<li class="woocommerce-order-overview__date date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
			</li>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
				<li class="woocommerce-order-overview__email email">
					<?php _e( 'Email:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_billing_email(); ?></strong>
				</li>
			<?php endif; ?>

			<li class="woocommerce-order-overview__total total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>

			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="woocommerce-order-overview__payment-method method">
					<?php _e( 'Payment method:', 'woocommerce' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</li>
			<?php endif; ?>

		</ul>

		<?php 
		do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); 
		
		//It display by shortcode
		remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
		do_action( 'woocommerce_thankyou', $order->get_id() ); 
	}
}