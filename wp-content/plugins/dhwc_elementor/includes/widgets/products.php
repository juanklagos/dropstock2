<?php
namespace DHWC_Elementor\Widgets;

class Widget_Products extends \Elementor\Widget_Base {
	public function get_name() {
		return 'dhwc_elementor_products';
	}
	
	public function get_title() {
		return __( 'WC Products', 'dhwc_elementor' );
	}
	
	public function get_categories() {
		return array('dhwc');
	}
	
	public function get_icon(){
		return array('dhwc');
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'dhwc_elementor_products',
			array(
				'label' => esc_html__( 'Settings', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'ids',
			array(
				'label' => __( 'Select products', 'dhwc_elementor' ),
				'type' => 'dhwc_elementor_ajaxselect2',
				'label_block' => true,
				'default' => '',
				'title' => __( 'Select products', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'per_page',
			array(
				'label' => __( 'Product Per Page', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 12,
				'title' => __( 'Product Per Page', 'dhwc_elementor' ),
			)
		);
		
		
		$this->add_control(
			'columns',
			array(
				'label' => __( 'Columns', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 4,
				'title' => __( 'Columns', 'dhwc_elementor' ),
			)
		);
		
		$this->add_control(
			'orderby',
			array(
				'label' => __( 'Products Ordering', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'title' => __( 'Products Ordering', 'dhwc_elementor' ),
				'options'=>array(
					'date'=>__ ( 'Publish Date', 'dhwc_elementor' ),
					'modified'=>__ ( 'Modified Date', 'dhwc_elementor' ),
					'rand'=>__ ( 'Random', 'dhwc_elementor' ),
					'title'=>__ ( 'Alphabetic', 'dhwc_elementor' ),
					'popularity'=>__ ( 'Popularity', 'dhwc_elementor' ),
					'rating'=>__ ( 'Rate', 'dhwc_elementor' ),
					'price'=>__ ( 'Price', 'dhwc_elementor' ),
				),
			)
		);
		
		$this->add_control(
			'order',
			array(
				'label' => __( 'Ascending or Descending', 'dhwc_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ASC',
				'title' => __( 'Ascending or Descending', 'dhwc_elementor' ),
				'options'=>array(
					'ASC'=>__ ( 'Ascending', 'dhwc_elementor' ),
					'DESC'=>__ ( 'Descending', 'dhwc_elementor' ),
				),
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings=$this->get_settings();
		$ids 		= !empty($settings['ids']) ? implode(',', $settings['ids']) : '';
		$per_page 	= !empty($settings['per_page']) ? $settings['per_page']	:	12;
		$columns 	= !empty($settings['columns']) ? $settings['columns']	: 4;
		$orderby 	= !empty($settings['orderby']) ? $settings['orderby']	: 'date';
		$order 		= !empty($settings['order']) ? $settings['order'] : '';
		if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
			$settings_params = array();
			if(!empty($ids)){
				$product_selected = get_posts(array(
					'include'	=> explode(',', $ids),
					'post_type'	=> 'product'
				));
				foreach ($product_selected as $p){
					$p_data 			= new \stdClass();
					$p_data->id			=	$p->ID;
					$p_data->text		=	sprintf( __( '#%s &ndash; %s', 'dhwc_elementor' ), $p->ID, $p->post_title );
					$p_data->selected	= true;
					$settings_params[] 	= $p_data;
				}
			}
			?>
			<div id="ids_select2_data" data-select2_data="<?php echo esc_attr(json_encode($settings_params))?>"></div>
			<?php
		}
		echo \WC_Shortcodes::products([
			'ids' 		=> $ids,
			'per_page' 	=> $per_page,
			'columns' 	=> $columns,
			'orderby' 	=> $orderby,
			'order' 	=> $order
		]);
	}
	
}