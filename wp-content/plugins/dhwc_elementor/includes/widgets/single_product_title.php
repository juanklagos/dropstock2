<?php
namespace DHWC_Elementor\Widgets;


use Elementor\Controls_Manager;

class Widget_Single_Product_Title extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_title';
	}
	
	public function get_title() {
		return __( 'WC Single Product Title', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Style', 'dhwc_elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_control(
			'header_size',
			[
				'label' => __( 'HTML Tag', 'dhwc_elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h1',
			]
		);
		
		$this->add_control(
			'color',
			array(
				'label' => __( 'Text Color', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
	
				'scheme' => array(
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .product_title' => 'color: {{VALUE}};',
				),
			)
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .product_title'
			)
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .product_title'
			)
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], 'class="product_title entry-title"', get_the_title() );
	}
}