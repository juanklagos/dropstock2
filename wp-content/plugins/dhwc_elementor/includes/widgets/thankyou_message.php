<?php
namespace DHWC_Elementor\Widgets;

class Widget_Thankyou_Message extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'dhwc_elementor_thankyou_message';
	}
	
	public function get_title() {
		return __( 'WC Thankyou Message', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_checkout');
	}
	
	public function get_icon(){
		return array('dhwc_checkout');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'failed_message',
			array(
				'label' => __( 'Order Failed Message', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again',
			)
		);
		
		$this->add_control(
			'received_message',
			array(
				'label' => __( 'Order Received Message', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Thank you. Your order has been received.',
			)
		);
		
		$this->add_control(
			'message_color',
			array(
				'label' => __( 'Message Color', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-thankyou-message' => 'color: {{VALUE}};'
				],
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woocommerce-thankyou-message',
			)
		);
		
		$this->end_controls_section();
	}
	
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$order = dhwc_elementor_get_last_order();
			if(!$order){
				echo __('Thankyou Message Placeholder','dhwc_elementor');
				return;
			}
		}else{
			$order = DHWC_Elementor()->current_order;
		}
		if($order){
			if($order->has_status('failed')){
				echo '<p class="woocommerce-thankyou-message woocommerce-thankyou-failed-message">'.esc_html($settings['failed_message']).'</p>';
				?>
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
				<?php
			}else{
				echo '<p class="woocommerce-thankyou-message woocommerce-thankyou-received-message">'.esc_html($settings['received_message']).'</p>';
			}
		}else{
			echo '<p class="woocommerce-thankyou-message woocommerce-thankyou-received-message">'.esc_html($settings['received_message']).'</p>';
		}
	}
}