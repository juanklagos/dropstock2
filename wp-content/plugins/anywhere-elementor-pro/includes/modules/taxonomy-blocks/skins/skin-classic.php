<?php
namespace Aepro\Modules\TaxonomyBlocks\Skins;

use Aepro\Aepro;
use Aepro\Helper;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Classic extends Skin_Base
{
    protected function _register_controls_actions()
    {
        parent::_register_controls_actions(); // TODO: Change the autogenerated stub
        add_action( 'elementor/element/ae-taxonomy-blocks/section_layout/before_section_end', [ $this, 'register_controls' ] );
        add_action( 'elementor/element/ae-taxonomy-blocks/classic_section_overlay_style/after_section_end', [ $this, 'update_style_controls' ] );
        add_action( 'elementor/element/ae-taxonomy-blocks/classic_section_overlay_style/after_section_end', [ $this, 'remove_style_controls' ] );
    }

    public function get_id()
    {
        return 'classic';
    }

    public function get_title()
    {
        return __('Classic', 'ae-pro');
    }

    public function register_controls(Widget_Base $widget)
    {

        $this->parent = $widget;

        $this->layout_controls();
        $this->title_controls();
        $this->overlay_controls();
        $this->image_controls();
        $this->count_controls();


    }
    public function register_style_controls()
    {
        parent::register_style_controls();

        $this->update_responsive_control(
            'block_min_height',
            [
                'default' => [
                    'size' => '',
                ]
            ]
        );

        $this->update_control(
                'title_background_color',
                [
                    'default' => '#fff'
                ]
        );

        $this->update_control(
            'title_align_vertical',
            [
                'options' 	=> [
                    'top' 	=> [
                        'title' 	=> __( 'Top', 'ae-pro' ),
                        'icon' 		=> 'eicon-v-align-top',
                    ],
                    'bottom' 		=> [
                        'title' 	=> __( 'bottom', 'ae-pro' ),
                        'icon' 		=> 'eicon-v-align-bottom',
                    ],
                ],
                'default' 		=> 'bottom',
                'prefix_class'	=> 'caption-block-align-',
            ]
        );

        $this->update_control(
            'title_align_horizontal',
            [
                'options' 	=> [
                    'left' 	=> [
                        'title' 	=> __( 'Left', 'ae-pro' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'center' 		=> [
                        'title' 	=> __( 'Center', 'ae-pro' ),
                        'icon' 		=> 'eicon-h-align-center',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'ae-pro' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                ],
            ]
        );
    }

    public function update_style_controls()
    {

        $this->update_control(
            'title_typography_font_family',
            [
                'default' => 'Poppins'
            ]
        );

        $this->update_control(
            'title_typography_font_size',
            [
                'default' => [
                    'unit' => 'px',
                    'size' => 18
                ]
            ]
        );

        $this->update_control(
            'overlay_color',
            [
                'default' => 'rgba(0,0,0,0.16)'
            ]
        );

        $this->update_responsive_control(
            'overlay_margin',
            [
                'default' => [
                    'size' => 10,
                ],
                'tablet_default' => [
                    'size' => 5,
                ],
            ]
        );

        $this->update_responsive_control(
            'overlay_margin_hover',
            [
                'default' => [
                    'size' => 0,
                ],
                'tablet_default' => [
                    'size' => 0,
                ],
            ]
        );

        $this->update_responsive_control(
            'overlay_opacity_hover',
            [
                'default' => [
                    'size' => 0
                ]
            ]
        );
    }

    function remove_style_controls(){

        //$this->remove_control('title_align_vertical');
        $this->remove_control('text_indent');
    }

    public function render()
    {
        // TODO: Implement render() method.

        $settings = $this->parent->get_settings_for_display();

        $terms = Aepro::$_helper->ae_taxonomy_terms($settings['ae_taxonomy'], $settings);

        $taxonomy = get_taxonomy( $settings['ae_taxonomy'] );

        $this->parent->add_render_attribute( 'term-list-wrapper', 'class', 'ae-term-skin-classic' );
        $this->parent->add_render_attribute( 'term-list-wrapper', 'class', 'ae-term-list-wrapper' );
        $this->parent->add_render_attribute( 'term-list-item', 'class', 'ae-term-list-item' );
        $this->parent->add_render_attribute( 'term-list-item-inner', 'class', 'ae-term-list-item-inner' );

        $this->parent->add_render_attribute( 'taxonomy-widget-wrapper', 'data-pid', get_the_ID() );
        $this->parent->add_render_attribute( 'taxonomy-widget-wrapper', 'data-wid', $this->parent->get_id() );
        $this->parent->add_render_attribute( 'taxonomy-widget-wrapper', 'data-source', $settings['ae_taxonomy'] );
        $this->parent->add_render_attribute( 'taxonomy-widget-wrapper', 'class', 'ae-taxonomy-widget-wrapper' );
        ?>
        <div <?php echo $this->parent->get_render_attribute_string('taxonomy-widget-wrapper'); ?>>
            <div <?php echo $this->parent->get_render_attribute_string('term-list-wrapper'); ?>>
                <?php
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $count = count( $terms );
                    $i = 0;
                    foreach ( $terms as $term ) {
                        $i++;
                        $image = '';
                        if($this->get_instance_value('show_image') == 'yes'){
                            $img_size = $this->get_instance_value('ae_taxonomy_image_size');
                            if($this->get_instance_value('image_type') == 'custom_field'){
                                $img = wp_get_attachment_image_src(get_term_meta($term->term_id, $this->get_instance_value('ae_taxonomy_bg_cf_field_key'), true), $img_size);
                                $image = $img[0];
                                //$this->parent->set_render_attribute( 'term-list-item', 'style', 'background-image: url(' . $image . ')'  );
                            }else{
                                //print_r($settings['taxonomy_image']);
                                $img = $img = wp_get_attachment_image_src($settings[$this->get_control_id('taxonomy_image')]['id'], $img_size);
                                //$image = $img[0];
                                $img = $this->get_instance_value('taxonomy_image');
                                $image = $img['url'];
                            }
                        }
                        //$this->render_title($settings, $this->parent, $term)
                        ?>
                        <div <?php echo $this->parent->get_render_attribute_string('term-list-item'); ?>>
                            <div <?php echo $this->parent->get_render_attribute_string('term-list-item-inner'); ?>>
                                <?php
                                $this->parent->set_render_attribute( 'term-image-wrapper', 'class', 'ae-term-image-wrapper' );
                                if($image != '') {
                                    ?>
                                    <div <?php echo $this->parent->get_render_attribute_string('term-image-wrapper'); ?>>
                                        <div class="ae-post-image">
                                            <img src="<?php echo $image; ?>"/>
                                        </div>
                                        <div class="term-overlay"></div>
                                    </div>
                                    <?php
                                }
                                /*else{ ?>
                                    <div <?php echo $this->parent->get_render_attribute_string('term-image-wrapper'); ?>>
                                            <div class="ae-post-image">
                                                <img src="<?php echo $helper->get_ae_placeholder_image_src(); ?>"/>
                                                <div class="term-overlay"></div>
                                            </div>
                                    </div>
                                    <?php
                                }*/
                                $title_html = '';
                                $this->parent->set_render_attribute( 'term-title-wrapper', 'class', 'ae-term-title-wrapper' );
                                $title_html .= "<div ". $this->parent->get_render_attribute_string('term-title-wrapper') . ">";
                                if($this->get_instance_value('show_title') == 'yes') {
                                    $this->parent->set_render_attribute( 'term-title-class', 'class', 'ae-element-term-title' );
                                    $term_title = $term->name;
                                    if($this->get_instance_value('strip_title') == 'yes'){
                                        if($this->get_instance_value('strip_mode') == 'word'){
                                            $term_title = wp_trim_words($term_title, $this->get_instance_value('strip_size'), $this->get_instance_value('strip_append'));
                                        }else{
                                            $term_title = rtrim(substr($term_title, 0, $this->get_instance_value('strip_size'))) . $this->get_instance_value('strip_append');
                                        }
                                    }
                                    if($this->get_instance_value('enable_title_link') == 'yes'){
                                        if($this->get_instance_value('title_new_tab') == 'yes'){
                                            $this->parent->set_render_attribute( 'term-link-class', 'target', '_blank' );
                                        }
                                        $title_html .= '<a ' . $this->parent->get_render_attribute_string('term-link-class') . ' href="'. esc_url(get_term_link($term)) .'">';
                                    }
                                    if($this->get_instance_value('show_count') == 'yes') {
                                        $term_title .= ' (' . $term->count . ')';
                                    }
                                    $title_html .= sprintf('<%1$s itemprop="name" %2$s>%3$s</%1$s>', $this->get_instance_value('html_tag'),$this->parent->get_render_attribute_string('term-title-class'),$term_title);

                                    if($this->get_instance_value('enable_title_link') == 'yes'){
                                        $title_html .= '</a>';
                                    }
                                    //echo '<a href="' . esc_url(get_term_link($term)) . '" alt="' . esc_attr(sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name)) . '">' . $term->name . '</a>';
                                }
                                $title_html .= '</div>';
                                echo $title_html;
                                ?>
                                <?php if($this->get_instance_value('overlay_enable_link') == 'yes'){
                                    $this->parent->set_render_attribute( 'term-overlay-link', 'class', 'overlay-link' );
                                    $this->parent->set_render_attribute( 'term-overlay-link', 'href', esc_url(get_term_link($term)) );
                                    if($this->get_instance_value('overlay_link_new_tab') == 'yes'){
                                        $this->parent->set_render_attribute( 'term-overlay-link', 'target', '_blank' );
                                    }
                                    ?>
                                    <a <?php echo $this->parent->get_render_attribute_string('term-overlay-link') ?>></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
}