<div class="dhwc-elementor-cart dhwc-elementor-cart-empty">
	
	<?php do_action( 'woocommerce_cart_is_empty' ); ?>
	
	<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_cart_empty') )?>
</div>