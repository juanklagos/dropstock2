<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Meta extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_meta';
	}
	
	public function get_title() {
		return __( 'WC Single Product Meta', 'dhwc_elementor' );
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
		global $product;
		if(dhwc_elementor_is_jupiter_theme()){
			?>
			<div class="mk_product_meta">
	
				<?php do_action( 'woocommerce_product_meta_start' ); ?>
	
				<?php
				$cat_count = sizeof( get_the_terms( $product->get_id(), 'product_cat' ) );
				$tag_count = sizeof( get_the_terms( $product->get_id(), 'product_tag' ) );
	
				 if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
	
					<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</span>
	
				<?php endif; ?>
	
				<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '.</span>' ); ?>
	
				<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '.</span>' ); ?>
	
				<?php do_action( 'woocommerce_product_meta_end' ); ?>
	
			</div>
			<?php
		}else{
			woocommerce_template_single_meta ();
		}
	}
	
}