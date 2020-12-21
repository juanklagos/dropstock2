<?php
namespace DHWC_Elementor\Widgets;

use Elementor\Widget_Base;

abstract class Widget_Single_Product_Base extends Widget_Base {
	public function render_content() {
		$switched = false;
		if((\Elementor\Plugin::$instance->editor->is_edit_mode() || (defined('DOING_AJAX') && DOING_AJAX)) && ($product_id = dhwc_elementor_find_product_by_template(get_the_ID()))){
			\Elementor\Plugin::$instance->db->switch_to_post($product_id);
			$switched = true;
		}
		$contrent = parent::render_content();
		if($switched){
			\Elementor\Plugin::$instance->db->restore_current_post();
		}
		return $contrent;
	}
}