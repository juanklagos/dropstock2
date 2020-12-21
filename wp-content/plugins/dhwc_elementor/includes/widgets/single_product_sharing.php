<?php
namespace DHWC_Elementor\Widgets;

class Widget_Single_Product_Sharing extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_sharing';
	}
	
	public function get_title() {
		return __( 'WC Single Product Sharing', 'dhwc_elementor' );
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
		if(dhwc_elementor_is_jupiter_theme()){
			?>
			<ul class="woocommerce-social-share">
				<li><a class="facebook-share" data-title="<?php the_title();?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>
				<li><a class="twitter-share" data-title="<?php the_title();?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-moon-twitter"></i></a></li>
				<li><a class="googleplus-share" data-title="<?php the_title();?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-googleplus"></i></a></li>
				<li><a class="pinterest-share" data-image="<?php get_the_post_thumbnail_url(); ?>" data-title="<?php echo get_the_title();?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-pinterest"></i></a></li>
			</ul>
			<?php
		}else{
			woocommerce_template_single_sharing ();
		}
	}
	
}