<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_FPD extends Widget_Single_Product_Base {
	
	public function get_name() {
		return 'dhwc_elementor_single_product_fpd';
	}
	
	public function get_title() {
		return __( 'WC Single FPD Designer', 'dhwc_elementor' );
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
		if (! empty ( $el_class )){
			echo '<div class=" ' . $el_class . '">';
		}
		?>
		<div class="dhwc-elementor-fpd">
			<style>#fpd-start-customizing-button~#fpd-start-customizing-button{display:none}</style>
			<?php 
			$FPD_Frontend_Product = new \FPD_Frontend_Product;
			$FPD_Frontend_Product->add_product_designer();
			?>
		</div>
		<?php 
	}
	
}