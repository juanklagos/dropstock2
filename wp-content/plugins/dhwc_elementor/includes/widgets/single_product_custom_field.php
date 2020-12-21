<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Custom_Field extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_custom_field';
	}
	
	public function get_title() {
		return __( 'WC Single Product Custom Field', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_single_product_custom_field',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		$this->add_control(
			'key',
			array(
				'label' => __( 'Field key name', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Field key name', 'dhwc_elementor' ),
			)
		);
	
		$this->add_control(
			'label',
			array(
				'label' => __( 'Label', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter label to display before key value.', 'dhwc_elementor' ),
			)
		);
		$this->end_controls_section();
	}
	
	protected function render() {
		global $post;
		$settings = $this->get_settings_for_display();
		$label = $settings['label'];
		if ( strlen( $label ) ) {
			$label_html = '<span class="dhwc_elementor_custom_field-label">' . esc_html( $label ) . '</span>';
		}
		if ( !empty( $settings['key'] ) && ($value = get_post_meta( $post->ID, $settings['key'], true)) ) :
		?>
		<div class="dhwc_elementor_custom_field <?php echo esc_attr( $css_class ) ?>">
			<?php echo $label_html ?>
			<?php echo apply_filters('dhwc_elementor_custom_field_value',$value, $settings['key'], $post);?>
		</div>
		<?php 
		endif;
	}
}