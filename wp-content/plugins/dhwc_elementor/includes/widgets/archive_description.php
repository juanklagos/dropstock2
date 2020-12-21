<?php
namespace DHWC_Elementor\Widgets;

class Widget_Archive_Description extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_archive_description';
	}
	
	public function get_title() {
		return __( 'WC Archive Description', 'dhwc_elementor' );
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
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			echo __('Archive description placeholder','dhwc_elementor');
			return;
		}
		woocommerce_taxonomy_archive_description();
	}
}