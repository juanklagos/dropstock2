<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Acf_Field extends Widget_Single_Product_Base {
	
	public function get_name() {
		return 'dhwc_elementor_single_product_acf_field';
	}
	
	public function get_title() {
		return __( 'WC ACF Custom Fields', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls() {
		
		$custom_fields = array ();
		if(function_exists('acf_get_field_groups')){
			$field_groups = acf_get_field_groups();
		}else{
			$field_groups = apply_filters ( 'acf/get_field_groups', array () );
		}
		
		foreach ( $field_groups as $field_group ) {
			if (is_array ( $field_group )) {
				if(function_exists('acf_get_fields')){
					$fields = acf_get_fields($field_group);
					if (! empty ( $fields )) {
						foreach ( $fields as $field ) {
							$custom_fields [$field ['name']] = $field ['label'];
						}
					}
		
				}else{
					$fields = apply_filters ( 'acf/field_group/get_fields', array (), $field_group ['id'] );
					if (! empty ( $fields )) {
						foreach ( $fields as $field ) {
							$custom_fields [$field ['name']] = $field ['label'];
						}
					}
				}
			}
		}
		
		$this->start_controls_section(
			'dhwc_elementor_acf_field',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'field',
			array(
				'label' => __( 'Field Name', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'title' => __( 'Field Name', 'dhwc_elementor' ),
				'options'=>$custom_fields,
			)
		);
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$field = ! empty( $settings['field'] ) ? $settings['field'] : '';
		?>
		<div class="dhwc_elementor_acf_field">
		<?php 
		$value = get_field($field);
		//filter to custom display
		$value = apply_filters('dhwc_elementor_acf_field', $value, $field);
		if( is_array($value) )
		{
			$value = implode(', ',$value);
		}
		echo do_shortcode($value);
		?>
		</div>
		<?php 
	}
	
}