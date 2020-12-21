<div class="dhwc-elementor-cart">
	
	<?php do_action( 'woocommerce_before_cart' ); ?>
	
	<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_cart') )?>
	
	<?php do_action( 'woocommerce_after_cart' ); ?>
</div>