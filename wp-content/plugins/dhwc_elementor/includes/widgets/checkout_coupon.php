<?php
namespace DHWC_Elementor\Widgets;

class Widget_Checkout_Coupon extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_checkout_coupon';
	}
	
	public function get_title() {
		return __( 'WC Checkout Coupon', 'dhwc_elementor' );
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
			woocommerce_checkout_coupon_form();
			return;
		}
		?>
		<style scoped>.dhwc-elementor-checkout .dhwc-elementor-checkout-coupon form.checkout_coupon{width: auto;}.dhwc-elementor-checkout .woocommerce-form-coupon-toggle{display: none;margin-bottom: 15px;}</style>
		<div class="dhwc-elementor-checkout-coupon"></div>
		<?php
		wc_enqueue_js('var coupon_form=jQuery(".dhwc-elementor-checkout form.woocommerce-form-coupon"),coupon_info=jQuery("div.woocommerce-form-coupon-toggle > .woocommerce-info");coupon_info.removeClass("woocommerce-info");var coupon_toggle=jQuery(".woocommerce-checkout div.woocommerce-form-coupon-toggle");jQuery(".dhwc-elementor-checkout-coupon").append(coupon_toggle),coupon_toggle.show(),coupon_form.insertAfter(coupon_toggle),jQuery(document).on("checkout_error updated_checkout",function(){jQuery("form.woocommerce-checkout").children(".woocommerce-error").prependTo(".dhwc-elementor-checkout")});');
	
	}
}