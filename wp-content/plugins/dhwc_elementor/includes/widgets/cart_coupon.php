<?php
namespace DHWC_Elementor\Widgets;

class Widget_Cart_Coupon extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_cart_coupon';
	}
	
	public function get_title() {
		return __( 'WC Cart Coupon', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_cart');
	}
	
	public function get_icon(){
		return array('dhwc_cart');
	}
	public function get_stack($with_common_controls = true) {
		$stack = parent::get_stack(false);
		$common_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( 'common' );
		$stack['controls'] = $common_widget->get_controls();
		$stack['tabs'] = $common_widget->get_tabs_controls();
		return $stack;
	}
	
	protected function render() {
		ob_start();
		?>
		<h2><?php _e( 'Coupon', 'woocommerce' ); ?></h2>
		<p class="form-row form-row-first">
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
		</p>
	
		<p class="form-row form-row-last">
			<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
		</p>
		
		<div class="clear"></div>
		<?php 
		
		if(!\Elementor\Plugin::$instance->editor->is_edit_mode()){
			wc_enqueue_js('jQuery(function(n){n(document).on("click",".woocommerce-coupon-form :input[type=submit]",function(o){n(":input[type=submit]",n(o.target).parents("form")).removeAttr("clicked"),n(o.target).attr("clicked","true")}),n(document.body).on("applied_coupon",function(){n(".woocommerce-coupon-form").removeClass("processing").unblock()}),n(".woocommerce-coupon-form").on("submit",function(o){n(document.activeElement),n(":input[type=submit][clicked=true]");var e,t,c=n(o.currentTarget);(t=e=c).is(".processing")||t.parents(".processing").length||e.addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}}),n(".woocommerce-cart-form").trigger("submit"),o.preventDefault(),o.stopPropagation()})});');
		}
		echo dhwc_elementor_cart_form_wrap(ob_get_clean(),'woocommerce-coupon-form');
	}
}