<?php
namespace DHWC_Elementor\Widgets;

class Widget_Checkout extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_checkout';
	}
	
	public function get_title() {
		return __( 'WC Checkout', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc');
	}
	
	public function get_icon(){
		return array('dhwc');
	}
	
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render() {
		echo \WC_Shortcodes::checkout([]);
	}
	
}