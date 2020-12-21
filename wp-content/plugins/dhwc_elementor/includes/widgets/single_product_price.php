<?php
namespace DHWC_Elementor\Widgets;


class Widget_Single_Product_Price extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_price';
	}
	
	public function get_title() {
		return __( 'WC Single Product Price', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_single_product_price_style',
			array(
				'label' => __( 'Style', 'dhwc_elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
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
					apply_filters('dhwc_elementor_single_product_price_style_selector', '{{WRAPPER}} .price') => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => apply_filters('dhwc_elementor_single_product_price_style_selector', '{{WRAPPER}} .price'),
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'text_shadow',
				'selector' => apply_filters('dhwc_elementor_single_product_price_style_selector', '{{WRAPPER}} .price'),
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render() {
		global $product;
		if(dhwc_elementor_is_jupiter_theme()){
			?>
			<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	
				<div itemprop="price" class="mk-single-price"><?php echo $product->get_price_html(); ?></div>
	
				<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
				<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
	
			</div>
			<?php
		}else{
			woocommerce_template_single_price();
		}
	}
	
}