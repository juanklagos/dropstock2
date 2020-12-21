<?php
namespace DHWC_Elementor\Widgets;

class Widget_Product_Categories extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_product_categories';
	}
	
	public function get_title() {
		return __( 'WC Product Categories', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc');
	}
	
	public function get_icon(){
		return array('dhwc');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_product_categories',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		
		$args = array(
			'orderby' => 'name',
			'hide_empty' => 0,
		);
		$options = array();
		$categories = get_terms( 'product_cat', $args );
		if( ! empty($categories)){
			foreach ($categories as $cat){
				$options[$cat->term_id] = $cat->name;
			}
		}
	
		$this->add_control(
			'ids',
			array(
				'label' => __( 'Categories', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'label_block' => true,
				'title' => __( 'Categories', 'dhwc_elementor' ),
				'options'=>$options,
				'multiple' => true,
			)
		);
		
		$this->add_control(
			'number',
			array(
				'label' => __( 'Number', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 4,
				'title' => __( 'Number', 'dhwc_elementor' ),
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
		
		$this->add_control(
			'hide_empty',
			array(
				'label' => __( 'Hide Empty', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'title' => __( 'Hide Empty', 'dhwc_elementor' ),
				'options'=>array(
					'1'=>__ ( 'Yes', 'dhwc_elementor' ),
					'0'=>__ ( 'No', 'dhwc_elementor' ),
				),
			)
		);
		
		$this->add_control(
			'parent',
			array(
				'label' => __( 'Parent', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Parent', 'dhwc_elementor' ),
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings=$this->get_settings_for_display();
		$ids = !empty($settings['ids']) ? implode(',', $settings['ids']):'';
		$number = !empty($settings['number']) ? $settings['number']:4;
		$columns = !empty($settings['columns']) ? $settings['columns']:'4';
		$orderby = !empty($settings['orderby']) ? $settings['orderby']:'date';
		$order = !empty($settings['order']) ? $settings['order']:'';
		$hide_empty = !empty($settings['hide_empty']) ? $settings['hide_empty']:'';
		$parent = !empty($settings['parent']) ? $settings['parent']:'';
		
		echo \WC_Shortcodes::product_categories([
			'limit'      => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'columns'    => $columns,
			'hide_empty' => $hide_empty,
			'parent'     => $parent,
			'ids'        => $ids,
		]);
	}
	
}