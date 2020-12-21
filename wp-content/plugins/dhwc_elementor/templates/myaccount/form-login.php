<div class="dhwc-elementor-account">
	<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
	
	<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_account_login') )?>
	
	<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
</div>