<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
	$template_id = $_REQUEST['dhwc_elementor_editor'];
	$post_class = 'dhwc-elementor-content '.apply_filters('dhwc_elementor_content_css_class','');
?>

<div id="product-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
	<?php 
	$document = \Elementor\Plugin::$instance->documents->get( $template_id );
	
	$attributes = $document->get_container_attributes();
	
	$attributes['class'] .= ' elementor-' . $template_id;
	
	echo '<div ' . \Elementor\Utils::render_html_attributes( $attributes ) . '></div>';
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
