<?php
namespace DHWC_Elementor\Widgets;

class Widget_Thankyou_Order_Details extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_thankyou_order_details';
	}
	
	public function get_title() {
		return __( 'WC Thankyou Order Details', 'dhwc_elementor' );
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
				echo __('Thankyou Order Details Placeholder','dhwc_elementor');
				return;
			}
		}else{
			$order = DHWC_Elementor()->current_order;
		}
		
		if(!empty($order) && !$order->has_status('failed')){
			woocommerce_order_details_table($order->get_id());
		}
	}
}