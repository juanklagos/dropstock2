<?php

function dhwc_elementor_get_template_key(){
	return '_dhwc_elementor_template';
}

function dhwc_elementor_get_default_template_key(){
	return '_dhwc_elementor_default_template';
}

function dhwc_elementor_is_jupiter_theme(){
	$result =  apply_filters('dhwc_elementor_is_jupiter_theme',function_exists('mk_woocommerce_assets'));
	return $result;
}

function dhwc_elementor_get_template($template_file){
	$find = array();
	$find[] = 'dhwc_elementor/'.$template_file;
	$template       = locate_template( $find );
	if ( ! $template || WC_TEMPLATE_DEBUG_MODE){
		$template = DHWC_ELEMENTOR_DIR . '/templates/' . $template_file;
	}
	return $template;
}

function dhwc_elementor_get_page_template_id($key){
	$page_id = apply_filters( 'dhwc_elementor_get_page_template_id', get_option( $key ), $key);
	
	return $page_id ? absint( $page_id ) : -1;
}

function dhwc_elementor_get_last_order(){
	global $wpdb;
	$statuses = array_keys(wc_get_order_statuses());
	$statuses = implode( "','", $statuses );
	
	// Getting last Order ID (max value)
	$results = $wpdb->get_col( "
        SELECT MAX(ID) FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'shop_order'
        AND post_status IN ('$statuses')
    " );
	return wc_get_order( reset($results) );
}

function dhwc_elementor_page_template_allow_taxonomies(){
	return apply_filters('dhwc_elementor_page_template_allow_taxonomies', array(
		'product_tag' => __('Product Tag page', 'dhwc_elementor'),
		'product_cat' => __('Product Category page', 'dhwc_elementor'),
		'product_brand' => __('Product Brand page', 'dhwc_elementor')
	));
}


function dhwc_elementor_get_product_types(){
	$product_types = wc_get_product_types();
	$product_types['downloadable'] = __( 'Downloadable product', 'dhwc_elementor' );
	$product_types['virtual'] = __( 'Virtual product', 'dhwc_elementor' );
	return $product_types;
}


function dhwc_elementor_the_product_content(){
	global $dhwc_elementor_single_product_template;
	$content = $dhwc_elementor_single_product_template->post_content;
	if(class_exists('\Elementor\Plugin')){
		$content = \Elementor\Plugin::$instance->frontend->get_builder_content($dhwc_elementor_single_product_template->ID);
	}
	if(is_callable(array( WC()->structured_data,'generate_product_data'))){
		WC()->structured_data->generate_product_data();
	}
	add_filter( 'dhwc_elementor_the_product_content', 'do_blocks', 9 );
	add_filter( 'dhwc_elementor_the_product_content', 'convert_smilies', 20 );
	add_filter( 'dhwc_elementor_the_product_content', 'capital_P_dangit', 11 );
	add_filter( 'dhwc_elementor_the_product_content', 'do_shortcode', 11 );
	add_filter( 'dhwc_elementor_the_product_content', 'prepend_attachment' );
	add_filter( 'dhwc_elementor_the_product_content', 'wp_make_content_images_responsive' );
	
	do_action('dhwc_elementor_before_the_product_content', $content);
	$content = apply_filters('dhwc_elementor_the_product_content',$content);
	$content = str_replace( ']]>', ']]&gt;', $content );
	echo $content;
}

function dhwc_elementor_the_page_template_content($content){
	add_filter( 'dhwc_elementor_the_page_template_content', 'do_blocks', 9 );
	add_filter( 'dhwc_elementor_the_page_template_content', 'wptexturize' );
	add_filter( 'dhwc_elementor_the_page_template_content', 'convert_smilies', 20 );
	add_filter( 'dhwc_elementor_the_page_template_content', 'shortcode_unautop' );
	add_filter( 'dhwc_elementor_the_page_template_content', 'prepend_attachment' );
	add_filter( 'dhwc_elementor_the_page_template_content', 'wp_make_content_images_responsive' );
	add_filter( 'dhwc_elementor_the_page_template_content', 'do_shortcode',11);
	// Format WordPress
	add_filter( 'dhwc_elementor_the_page_template_content', 'capital_P_dangit', 11 );
	
	$content = apply_filters('dhwc_elementor_the_page_template_content', $content);
	$content = str_replace( ']]>', ']]&gt;', $content );
	
	echo $content;
}

function dhwc_elementor_cart_form_wrap($content, $form_class=''){
	ob_start();
	?>
	<form class="<?php echo esc_attr($form_class)?>" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php echo $content?>
	</form>
	<?php 
	return apply_filters('dhwc_elementor_cart_form_wapper_output', ob_get_clean());
}

function dhwc_elementor_the_archive_content_template(){
	global $dhwc_elementor_product_archive_content_template;
	echo '<div class="dhwc-elementor-product-archive">';
	echo \Elementor\Plugin::$instance->frontend->get_builder_content($dhwc_elementor_product_archive_content_template->ID);
	echo '</div>';
}

function dhwc_elementor_product_formatted_name(WC_Product $product){
	$identifier = '#' . $product->get_id();
	return sprintf('%s &ndash; %s', $identifier, $product->get_title() );
}

function dhwc_elementor_ajax_select2_search_products(){
	header( 'Content-Type: application/json; charset=utf-8' );
	
	$term = (string) sanitize_text_field( stripslashes( $_GET['term'] ) );
	
	
	if (empty($term)) die();
	
	$post_types = array('product', 'product_variation');
	
	if ( is_numeric( $term ) ) {
	
		$args = array(
			'post_type'			=> $post_types ,
			'post_status'	 	=> 'publish',
			'posts_per_page' 	=> -1,
			'post__in' 			=> array(0, $term),
			'fields'			=> 'ids'
		);
	
		$args2 = array(
			'post_type'			=> $post_types,
			'post_status'	 	=> 'publish',
			'posts_per_page' 	=> -1,
			'post_parent' 		=> $term,
			'fields'			=> 'ids'
		);
	
		$args3 = array(
			'post_type'			=> $post_types,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
				array(
					'key' 	=> '_sku',
					'value' => $term,
					'compare' => 'LIKE'
				)
			),
			'fields'			=> 'ids'
		);
	
		$posts = array_unique(array_merge( get_posts( $args ), get_posts( $args2 ), get_posts( $args3 ) ));
	
	} else {
	
		$args = array(
			'post_type'			=> $post_types,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			's' 				=> $term,
			'fields'			=> 'ids'
		);
	
		$args2 = array(
			'post_type'			=> $post_types,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
				array(
					'key' 	=> '_sku',
					'value' => $term,
					'compare' => 'LIKE'
				)
			),
			'fields'			=> 'ids'
		);
	
		$posts = array_unique(array_merge( get_posts( $args ), get_posts( $args2 ) ));
	
	}
	
	$found_products = array();
	
	if ( $posts ) foreach ( $posts as $post ) {
	
		$product = get_product( $post );
	
		$found_products[ $post ] = dhwc_elementor_product_formatted_name($product);
	
	}
	
	echo json_encode( $found_products );
	
	die();
}
add_action('wp_ajax_dhwc_elementor_ajax_select2_search_products', 'dhwc_elementor_ajax_select2_search_products');

function dhwc_elementor_find_product_by_template($template_id){
	$product_id = 0;
	$args = array(
		'posts_per_page'      => 1,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'meta_query' => array(
			array(
				'key' => dhwc_elementor_get_template_key(),
				'value' => $template_id
			)
		)
	);
	$products = get_posts($args);
	if(!empty($products)){
		foreach ($products as $product){
			$product_id = $product->ID;
		}
	}else{
		$term_args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'meta_query' => array(
				array(
					'key'       => dhwc_elementor_get_template_key(),
					'value'     => $template_id
				)
			)
		);
		$terms = get_terms($term_args);
		if(!empty($terms)){
			$term = $terms[0];
			$args = array(
				'posts_per_page'      => 1,
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'tax_query' => array(
					array(
						'taxonomy'   => 'product_cat',
						'field'    => 'id',
						'terms'    => $term->term_id
					)
				)
			);
			$products = get_posts($args);
			foreach ($products as $product){
				$product_id = $product->ID;
			}
		}
	}
	if(empty($product_id)){
		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1
		);
		
		$single_product = new WP_Query( $args );
		if($single_product->have_posts()){
			while ($single_product->have_posts()){
				$single_product->the_post();
				$product_id = get_the_ID();
			}
		}
		wp_reset_postdata();
	}
	$product_id = apply_filters('dhwc_elementor_find_product_by_template', $product_id, $template_id);
	return (int) $product_id;
}

function dhwc_elementor_get_latest_product(){
	$args = array(
		'posts_per_page'      => 1,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1
	);
	$product = new WP_Query( $args );
	if(!$product->have_posts()){
		return false;
	}
	return current($product->posts);
}

function dhwc_elementor_shortcode_placeholder($content){
	$args = array(
		'posts_per_page'      => 1,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1
	);
	$dhwc_elementor_shortcode_placeholder_id = apply_filters('dhwc_elementor_shortcode_placeholder_id',0);
	if ( !empty($dhwc_elementor_shortcode_placeholder_id) ) {
		$args['p'] = absint( $dhwc_elementor_shortcode_placeholder_id );
	}
	$single_product = new WP_Query( $args );
	if($single_product->have_posts()){
		while ($single_product->have_posts()){
			$single_product->the_post();
			do_action('dhwc_elementor_shortcode_placeholder');
			$content = do_shortcode($content);
		}
	}
	wp_reset_postdata();
	return $content;
}

function dhwc_elementor_page_dropdown($args=''){
	$post_type =  'dhwc_template';
	if('page' != $post_type && post_type_exists($post_type)){
		$defaults = array(
			'depth' => 0,
			'child_of' => 0,
			'selected' => 0,
			'echo' => 1,
			'name' => 'page_id',
			'id' => '',
			'class' => '',
			'suppress_filters' 	=> false,
			'show_option_none' => '',
			'show_option_no_change' => '',
			'option_none_value' => '',
			'post_type'=>$post_type,
			'posts_per_page'=>-1
		);
	
		$r = wp_parse_args( $args, $defaults );
		$get_args = $r;
		
		if(isset($get_args['name'])){
			unset($get_args['name']);
		}
		
		$pages = get_posts( $get_args );
		$output = '';
		// Back-compat with old system where both id and name were based on $name argument
		if ( empty( $r['id'] ) ) {
			$r['id'] = $r['name'];
		}
	
		$class = '';
		if ( ! empty( $r['class'] ) ) {
			$class = " class='" . esc_attr( $r['class'] ) . "'";
		}

		$output = "<select name='" . esc_attr( $r['name'] ) . "'" . $class . " id='" . esc_attr( $r['id'] ) . "'>\n";
		if ( $r['show_option_no_change'] ) {
			$output .= "\t<option value=\"-1\">" . $r['show_option_no_change'] . "</option>\n";
		}
		if ( $r['show_option_none'] ) {
			$output .= "\t<option value=\"" . esc_attr( $r['option_none_value'] ) . '">' . $r['show_option_none'] . "</option>\n";
		}
		if ( ! empty( $pages ) ) {
			$output .= walk_page_dropdown_tree( $pages, $r['depth'], $r );
		}
		$output .= "</select>\n";
		
		if ( $r['echo'] ) {
			echo $output;
		}
		return $output;
	}else{
		return wp_dropdown_pages($args);
	}
}

function dhwc_elementor_get_custom_template($post,$need_term=false){
	$product = wc_get_product();
	$default_template_id = (int) get_option(dhwc_elementor_get_default_template_key());
	$template_id = 0;
	$template_by = null;
	
	//Find template in product meta
	if($product_template_id = get_post_meta($product->get_id(), dhwc_elementor_get_template_key(), true)) {
		$template_id = $product_template_id;
	}else{
		//Find template by Product Type
		$product_type = $product->get_type();
		$product_type_template_id = 0;
		if('simple' === $product_type){
			//get template by product sub-type
			if($product->is_downloadable()){
				$template_by = __('Product type downloadable ','dhwc_elementor');
				$product_type_template_id = (int) get_option('dhwc_gutenberg_template_product_downloadable');
			}elseif ($product->is_virtual()){
				$template_by = __('Product type virtual ','dhwc-gutenberg');
				$product_type_template_id = (int) get_option('dhwc_gutenberg_template_product_virtual');
			}
			//use product type template if sub-type is null
			if(empty($product_type_template_id)){
				$product_type_template_id = (int) get_option('dhwc_gutenberg_template_product_'.$product_type);
			}
		}else{
			$product_type_template_id = (int) get_option('dhwc_gutenberg_template_product_'.$product_type);
		}
		
		if(!empty($product_type_template_id)){
			if(null === $template_by){
				$template_by = __('Product type','dhwc_elementor').' '.$product_type;
			}
			$template_id = $product_type_template_id;
		}else{
			//Find template by product category
			$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
			foreach ( $categories as $category ){
				if($product_category_template = (int) get_term_meta($category->term_id,dhwc_elementor_get_template_key(),true)){
					$template_by = __('Product Category', 'dhwc_elementor').' '.$category->name;
					$template_id = $product_category_template;
					break;
				}
			}
		}
	}
	
	$template_id = apply_filters('dhwc_gutenberg_get_product_template', $template_id);
	
	if(empty($template_id)){
		if(!empty($default_template_id)){
			$template_by = __('Default Template', 'dhwc_elementor');
		}
		$template_id = $default_template_id;
	}
	
	if($need_term){
		return [
			'template_id' => absint($template_id),
			'template_by' => $template_by
		];
	}
	
	return $template_id;
}