<?php
namespace DHWC_Elementor;

class Admin{
	private static $_instance = null;
	/**
	 * 
	 * @return \DHWC_Elementor\Admin
	 */
	public static function get_instance() {
		if ( is_null(self::$_instance) ){
			self::$_instance = new self;
		}
	
		return self::$_instance;
	}
	
	public function init(){
		//product meta data
		add_action('add_meta_boxes', array(&$this,'add_meta_boxes'));
		add_action( 'save_post', array(&$this,'save_product_meta_data'),1,2 );
		
		//product category form
		add_action( 'product_cat_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 10, 2 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );
		
		//Product term form
		add_action('current_screen', array($this,'create_term_fields'));
		add_action( 'created_term', array( $this, 'save_term_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_term_fields' ), 10, 3 );
		
		add_filter( 'woocommerce_get_settings_pages', array($this,'add_settings') );
	}
	
	public function create_term_fields(){
		$product_taxonomies = dhwc_elementor_page_template_allow_taxonomies();
		foreach ((array) $product_taxonomies as $tax_name=>$tax_label){
			add_action( $tax_name.'_add_form_fields', array( $this, 'add_term_fields' ), 5 );
			add_action( $tax_name.'_edit_form_fields', array( $this, 'edit_term_fields' ), 5, 2 );
		}
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		foreach ($attribute_taxonomies as $attribute){
			if(!$attribute->attribute_public){
				continue;
			}
			$attribute_taxonomy_name = wc_attribute_taxonomy_name($attribute->attribute_name);
			add_action( $attribute_taxonomy_name.'_add_form_fields', array( $this, 'add_term_fields' ), 5 );
			add_action( $attribute_taxonomy_name.'_edit_form_fields', array( $this, 'edit_term_fields' ), 5, 2 );
		}
	}
	
	public function add_term_fields($taxonomy){
		$tax = get_taxonomy($taxonomy);
		?>
		<div class="form-field">
			<label for="dhwc_elementor_cat_product"><?php _e( 'Content Template', 'dhwc_elementor' ); ?></label>
			<?php 
			echo str_replace(' id=', " style='width:100%' data-placeholder='" . __( 'Select a template&hellip;','dhwc_elementor') .  "' class='wc-enhanced-select-nostd' id=", self::dropdown_template( 0, 'dhwc_page_template', 'dhwc_elementor_term_content_template') );
			?>
			<p><?php echo sprintf(__('Select content template for this %s','dhwc_elementor'), $tax->label)?></p>
		</div>
		<?php
	}
	
	public function edit_term_fields($term, $taxonomy){
		$content_template_id = get_term_meta( $term->term_id, '_dhwc_elementor_term_content_template', true );
		$tax = get_taxonomy($taxonomy);
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Content Template', 'dhwc_elementor' ); ?></label></th>
			<td>
				<?php echo str_replace(' id=', " style='width:100%' data-placeholder='" . __( 'Select a template&hellip;','dhwc_elementor') .  "' class='wc-enhanced-select-nostd' id=", self::dropdown_template( $content_template_id, 'dhwc_page_template', 'dhwc_elementor_term_content_template') ); ?>
				<p><?php echo sprintf(__('Select content template for this %s','dhwc_elementor'), $tax->label)?></p>
			</td>
		</tr>
		<?php
	}
	
	public static function dropdown_template($selected = 0, $post_type , $name = 'dhwc_gutenberg_template'){
		return dhwc_elementor_page_dropdown([
			'post_type' 		=> $post_type,
			'post_status'		=> 'publish,private',
			'name'  			=> $name,
			'show_option_none'	=> ' ',
			'echo'				=> false,
			'parent'			=> 0,
			'selected' 			=> $selected
		]);
	}
	
	public function save_term_fields($term_id, $tt_id, $taxonomy){
		if( isset($_POST['dhwc_elementor_term_content_template']) && !empty($_POST['dhwc_elementor_term_content_template'])){
			update_term_meta( $term_id, '_dhwc_elementor_term_content_template', absint( $_POST['dhwc_elementor_term_content_template'] ) );
		}else{
			delete_term_meta($term_id,  '_dhwc_elementor_term_content_template');
		}
	}
	
	public function add_settings($settings){
		$settings[] = include DHWC_ELEMENTOR_DIR.'/includes/settings.php';
		return $settings;
	}
	
	public function add_meta_boxes(){
		add_meta_box('dhwc_elementor_metabox', __('Product Template','dhwc_elementor'), array(&$this,'add_product_meta_box'), 'product','side');
	}
	
	public function add_product_meta_box(){
		global $post;
		
		$product_template = dhwc_elementor_get_custom_template(null, true);
		if(!empty($product_template['template_by'])){
			echo '<span style="display: block;margin-bottom: 10px;margin-top: 20px;">';
			echo __('Currently use content template by:','dhwc_elementor').' <strong>'.$product_template['template_by'].'</strong>';
			echo '</span>';
		}
	
		$args = array(
			'post_status' 		=> 'publish,private',
			'name'				=> dhwc_elementor_get_template_key(),
			'show_option_none'	=> ' ',
			'echo'				=> false,
			'selected'			=> get_post_meta(get_the_ID(), dhwc_elementor_get_template_key(), true)
		);
		
		wp_nonce_field ('dhwc_elementor_metabox_nonce', 'dhwc_elementor_metabox_nonce',false);
		
		echo str_replace(' id=', " style='width:100%' data-placeholder='" . __( 'Select a template&hellip;','dhwc_elementor') .  "' class='wc-enhanced-select-nostd' id=", dhwc_elementor_page_dropdown( $args ) );
	}
	
	public function save_product_meta_data($post_id,$post){
		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}
		
		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce
		if (empty ( $_POST ['dhwc_elementor_metabox_nonce'] ) || ! wp_verify_nonce ( $_POST ['dhwc_elementor_metabox_nonce'], 'dhwc_elementor_metabox_nonce' )) {
			return;
		}
		
		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}
		
		// Check user has permission to edit
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$key = dhwc_elementor_get_template_key();
		if(isset($_POST[$key]) && !empty($_POST[$key])){
			update_post_meta( $post_id, $key, absint($_POST[$key]) );
		}else{
			delete_post_meta( $post_id, $key);
		}
		
	}
	
	public function add_category_fields(){
	
		?>
		<div class="form-field">
			<label for="dhwc_elementor_cat_product"><?php _e( 'Single Product Tempalte', 'dhwc_elementor' ); ?></label>
			<?php 
			$args = array(
					'post_status' => 'publish,private',
					'name'=>dhwc_elementor_get_template_key(),
					'show_option_none'=>' ',
					'suppress_filters' 	=> false,
					'echo'=>false,
			);
			echo str_replace(' id=', " style='width:100%' data-placeholder='" . __( 'Select a template&hellip;','dhwc_elementor') .  "' class='wc-enhanced-select-nostd' id=", dhwc_elementor_page_dropdown( $args ) );
			
			?>
			<p><?php _e('Select product content template for all products of this category','dhwc_elementor')?></p>
		</div>
		
		<?php
		}
		
		public function edit_category_fields( $term, $taxonomy ) {
			wp_enqueue_script( 'ajax-chosen' );
			wp_enqueue_script( 'chosen' );
			$product_template_id = get_woocommerce_term_meta( $term->term_id, dhwc_elementor_get_template_key(), true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Single Product Tempalte', 'dhwc_elementor' ); ?></label></th>
			<td>
				<?php 
				$args = array(
					'post_status' => 'publish,private',
					'name'=>dhwc_elementor_get_template_key(),
					'show_option_none'=>' ',
					'echo'=>false,
					'selected'=>absint($product_template_id)
				);
				echo str_replace(' id=', " style='width:100%' data-placeholder='" . __( 'Select a template&hellip;','dhwc_elementor') .  "' class='wc-enhanced-select-nostd' id=", dhwc_elementor_page_dropdown( $args ) );
				
				?>
				<p><?php _e('Select product content template for all products of this category','dhwc_elementor')?></p>
			</td>
		</tr>
		<?php
		}
		
		public function save_category_fields( $term_id, $tt_id, $taxonomy ) {
			$key = dhwc_elementor_get_template_key();
			if( isset($_POST[$key]) && !empty($_POST[$key])){
				update_woocommerce_term_meta( $term_id, $key, absint( $_POST[$key] ) );
			}else{
				delete_woocommerce_term_meta($term_id,  $key);
			}
		}

}

\DHWC_Elementor\Admin::get_instance()->init();