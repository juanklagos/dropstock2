<?php
namespace DHWC_Elementor\Widgets;

class Widget_Product_Attribute extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_product_attribute';
	}
	
	public function get_title() {
		return __( 'WC Product Attribute', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc');
	}
	
	public function get_icon(){
		return array('dhwc');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_product_attribute',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'attribute',
			array(
				'label' => __( 'Attribute', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Attribute', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'filter',
			array(
				'label' => __( 'Filter', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Filter', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'per_page',
			array(
				'label' => __( 'Product Per Page', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 12,
				'title' => __( 'Product Per Page', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'columns',
			array(
				'label' => __( 'Columns', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 4,
				'title' => __( 'Columns', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'orderby',
			array(
				'label' => __( 'Products Ordering', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'title' => __( 'Products Ordering', 'dhwc_elementor' ),
				'options'=>array(
					'date'=>__ ( 'Publish Date', 'dhwc_elementor' ),
					'modified'=>__ ( 'Modified Date', 'dhwc_elementor' ),
					'rand'=>__ ( 'Random', 'dhwc_elementor' ),
					'title'=>__ ( 'Alphabetic', 'dhwc_elementor' ),
					'popularity'=>__ ( 'Popularity', 'dhwc_elementor' ),
					'rating'=>__ ( 'Rate', 'dhwc_elementor' ),
					'price'=>__ ( 'Price', 'dhwc_elementor' ),
				),
			)
		);
		
		$this->add_control(
			'order',
			array(
				'label' => __( 'Ascending or Descending', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ASC',
				'title' => __( 'Ascending or Descending', 'dhwc_elementor' ),
				'options'=>array(
					'ASC'=>__ ( 'Ascending', 'dhwc_elementor' ),
					'DESC'=>__ ( 'Descending', 'dhwc_elementor' ),
				),
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings=$this->get_settings_for_display();
		$attribute = !empty($settings['attribute']) ? $settings['attribute']:'';
		$filter = !empty($settings['filter']) ? $settings['filter']:'';
		$per_page = !empty($settings['per_page']) ? $settings['per_page']:12;
		$columns = !empty($settings['columns']) ? $settings['columns']:'4';
		$orderby = !empty($settings['orderby']) ? $settings['orderby']:'date';
		$order = !empty($settings['order']) ? $settings['order']:'';
		
		echo \WC_Shortcodes::product_attribute([
			'attribute' => $attribute,
			'filter' => $filter,
			'per_page' => $per_page,
			'columns' => $columns,
			'orderby' => $orderby,
			'order' => $order
		]);
	}
	
}