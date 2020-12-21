<div class="dhwc-elementor-checkout">
<?php

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content(dhwc_elementor_get_page_template_id('dhwc_elementor_page_template_checkout' ) ) ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

</div>