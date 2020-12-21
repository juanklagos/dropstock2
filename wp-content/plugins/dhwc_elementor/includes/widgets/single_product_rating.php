<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Rating extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_rating';
	}
	
	public function get_title() {
		return __( 'WC Single Product Rating', 'dhwc_elementor' );
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
		$count   = $product->get_rating_count();
		$average = $product->get_average_rating();
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			if($count <= 0){
				echo __('No Rating for this product','dhwc_elementor');
				return;
			}
		}
		if(dhwc_elementor_is_jupiter_theme()){
			
			if ( $count > 0 ) : 
		?>
			
			<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
					<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
						<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?>
					</span>
				</div>
				<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $count, 'woocommerce' ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' ); ?>)</a>
			</div>
			<?php
			endif;
		}else{
			woocommerce_template_single_rating();
		}
	}
	
}