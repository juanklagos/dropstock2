<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Data_Tabs extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_data_tabs';
	}
	
	public function get_title() {
		return __( 'WC Single Product Data Tabs', 'dhwc_elementor' );
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
		woocommerce_output_product_data_tabs ();
	}
	
}