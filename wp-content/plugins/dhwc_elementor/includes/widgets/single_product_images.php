<?php
namespace DHWC_Elementor\Widgets;

use Elementor\Controls_Manager;

class Widget_Single_Product_Images extends Widget_Single_Product_Base {
	public function get_name() {
		return 'dhwc_elementor_single_product_images';
	}
	
	public function get_title() {
		return __( 'WC Single Product Images', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc_single_product');
	}
	
	public function get_icon(){
		return array('dhwc_single_product');
	}
	
	protected function _register_controls(){
		$this->start_controls_section(
			'dhwc_elementor_single_product_images_style',
			array(
				'label' => __( 'Style', 'dhwc_elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_control(
			'gallery_type',
			[
				'label' => __( 'Gallery Type', 'dhwc_elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Deafult by theme', 'dhwc_elementor'),
					'vertical' => __('Thumbnails vertical', 'dhwc_elementor'),
					'horizontal' => __('Thumbnails horizontal', 'dhwc_elementor'),
					'overlay' => __('Thumbnails overlay', 'dhwc_elementor'),
				],
				'default' => '',
			]
		);
		
		$this->add_control(
			'thumbnail_columns',
			[
				'label' => __( 'Thumbnail Columns', 'dhwc_elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 6,
			]
		);
		
		$this->add_control(
			'enable_zoom',
			[
				'label' => __( 'Enable Zoom', 'dhwc_elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'dhwc_elementor' ),
				'label_off' => __( 'No', 'dhwc_elementor' ),
			]
		);
		
		$this->add_control(
			'enable_lightbox',
			[
				'label' => __( 'Enable Lightbox', 'dhwc_elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'dhwc_elementor' ),
				'label_off' => __( 'No', 'dhwc_elementor' ),
			]
		);
		
		$this->end_controls_section();
	}
	
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		global $product;
		
		ob_start();
		
		if(!empty($settings['gallery_type'])){
			wp_enqueue_script('dhwc-elementor-product-image',DHWC_ELEMENTOR_URL.'/assets/js/product-image.min.js', ['slick'], DHWC_ELEMENTOR_VERSION, true);
			
			if('yes'===$settings['enable_zoom'] && !current_theme_supports( 'wc-product-gallery-zoom' )){
				wp_enqueue_script( 'zoom', plugins_url( 'assets/js/zoom/jquery.zoom.min.js', WC_PLUGIN_FILE ),array( 'jquery' ),'1.7.21',true);
			}
			if('yes'===$settings['enable_lightbox'] && !current_theme_supports( 'wc-product-gallery-lightbox' )){
				wp_register_script( 'photoswipe', plugins_url( 'assets/js/photoswipe/photoswipe.min.js', WC_PLUGIN_FILE ),array(),'4.1.1',true);
				wp_enqueue_script( 'photoswipe-ui-default', plugins_url( 'assets/js/photoswipe/photoswipe-ui-default.min.js', WC_PLUGIN_FILE ),array( 'photoswipe' ),'4.1.1',true);
				
				wp_register_style( 'photoswipe', plugins_url( 'assets/css/photoswipe/photoswipe.css', WC_PLUGIN_FILE ),array(),'4.1.1');
				wp_enqueue_style( 'photoswipe-default-skin', plugins_url( 'assets/css/photoswipe/default-skin/default-skin.css', WC_PLUGIN_FILE ),array( 'photoswipe' ),'4.1.1');
				add_action( 'wp_footer', [ __CLASS__,'photoswipe_template' ],100);
			}
			
			$gallery_class 		= 'no-thumbnails';
			$thumbnail_size    	= apply_filters('dhwc_elementor_product_gallery_thumbnail_size', 'woocommerce_gallery_thumbnail');
			
			
			$post_thumbnail_id  = $product->get_image_id();
			
			$gallery_html = $thumbnail_html = $main_thumbnail_html = '';
			
			add_filter('woocommerce_gallery_image_size', [ $this,'product_gallery_image_size' ],10000);
			
			$gallery_as_main_image = true;
			
			if ( $post_thumbnail_id ) {
				if( apply_filters('dhwc_elementor_product_use_main_image', true) ){
					$gallery_as_main_image = false;
					$post_thumbnail_url = wp_get_attachment_image_url( $post_thumbnail_id, $thumbnail_size );
					$main_thumbnail_html .= '<div class="woocommerce-product-gallery__thumbnail"><div class="slick-image--border">'.sprintf( '<img src="%s" data-o_src="%s" />', $post_thumbnail_url, $post_thumbnail_url ).'</div></div>';
					$gallery_html = wc_get_gallery_image_html( $post_thumbnail_id, true );
				}
			} else {
				$gallery_html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$gallery_html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'dhwc-gutenberg' ) );
				$gallery_html .= '</div>';
			}
			
			$gallery_html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $gallery_html, $post_thumbnail_id );
			$attachment_ids = $product->get_gallery_image_ids();
			
			if ( $attachment_ids && $post_thumbnail_id ) {
				$gallery_class = 'with-thumbnails';
				$thumbnail_html .= $main_thumbnail_html;
				$i = 0;
				foreach ( $attachment_ids as $attachment_id ) {
					$i ++;
					$post_thumbnail_url = wp_get_attachment_image_url( $attachment_id, $thumbnail_size );
					
					$thumbnail_html .= '<div class="woocommerce-product-gallery__thumbnail"><div class="slick-image--border">'.sprintf( '<img src="%s" data-o_src="%s" />', $post_thumbnail_url, $post_thumbnail_url ).'</div></div>';
					if($gallery_as_main_image && 1===$i){
						$main_image = true;
					}else{
						$main_image = false;
					}
					$gallery_html .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id, $main_image ), $attachment_id );
					
				}
				
				$thumbnail_html = '<div class="dhwc-elementor-product-gallery__thumbnails" data-mobile-thumbnail-columns="4" data-thumbnail-columns="'.esc_attr(absint($settings['thumbnail_columns'])).'" data-vertical="'.('vertical'===$settings['gallery_type'] ? 'true' : 'false').'" data-arrows="true" data-mobile-arrows="false">'.$thumbnail_html.'</div>';
			}
			
			remove_filter('woocommerce_gallery_image_size', [ $this,'product_gallery_image_size'],10000);
			
			?>
			<div class="dhwc-elementor-product-gallery <?php echo esc_attr($gallery_class)?> is-<?php echo esc_attr($settings['gallery_type'])?>">
				<div data-zoom="<?php echo esc_attr($settings['enable_zoom']) ?>" data-lightbox="<?php echo esc_attr($settings['enable_lightbox'])?>" class="images dhwc-elementor-product-gallery__images">
					<?php echo $gallery_html ?>
				</div>
				<?php echo $thumbnail_html ?>
			</div>
			<?php 
			
		}else{
			woocommerce_show_product_sale_flash();
			woocommerce_show_product_images();
		}
	}
	
	public function product_gallery_image_size(){
		return apply_filters('dhwc_elementor_product_gallery_image_size', 'woocommerce_single');
	}
	
	
	public static function photoswipe_template(){
		wc_get_template( 'single-product/photoswipe.php' );
	}
}