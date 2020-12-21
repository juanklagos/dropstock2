<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Upsell_Products extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_upsell_products';
	}
	
	public function get_title() {
		return __( 'WC Single Product Upsell', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_single_product_settings',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label' => __( 'Product Per Page', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 4,
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
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$posts_per_page = ! empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 4;
		$columns = ! empty( $settings['columns'] ) ? $settings['columns'] : 4;
		$orderby = ! empty( $settings['orderby'] ) ? (int)$settings['orderby'] : 'date';
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			global $product;
			$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
			$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;
			if(empty($upsells)){
				echo __('No Products Upsells for this product','dhwc_elementor');
			}
			return;
		}
		
		woocommerce_upsell_display ( $posts_per_page, $columns, $orderby);
	}
	
}