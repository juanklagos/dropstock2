<?php
namespace DHWC_Elementor\Widgets;

class Widget_Archive_Title extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_archive_title';
	}
	
	public function get_title() {
		return __( 'WC Archive Title', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_archive');
	}
	
	public function get_icon(){
		return array('dhwc_archive');
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
				'type' => \Elementor\Controls_Manager::SELECT,
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
					'{{WRAPPER}} .woocommerce-products-header__title' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woocommerce-products-header__title',
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-products-header__title',
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], 'class="woocommerce-products-header__title page-title"', get_the_title() );
	}
}