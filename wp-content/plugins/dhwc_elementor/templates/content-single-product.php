<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/dhwc_elementor/content-single-product.php
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
	 $class = 'dhwc-elementor-content';
	 
	 if(dhwc_elementor_is_jupiter_theme()){
	 	$class .=' mk-product style-default';
	 }
	 
	 $class = $class.' '.apply_filters('dhwc_elementor_content_css_class','');
?>

<div id="product-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<?php dhwc_elementor_the_product_content();?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
