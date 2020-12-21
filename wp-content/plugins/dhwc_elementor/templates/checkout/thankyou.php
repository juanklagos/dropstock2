<div class="dhwc-elementor-checkout dhwc-elementor-thankyou">
	
	<?php if ( $order ) DHWC_Elementor()->current_order = $order; ?>
	
	<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_checkout_thankyou' ) ) ?>
</div>