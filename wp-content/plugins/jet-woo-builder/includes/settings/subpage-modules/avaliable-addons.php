<?php
namespace Jet_Woo_Builder\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Avaliable_Addons extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-woo-builder-avaliable-addons';
	}

	/**
	 * [get_subpage_slug description]
	 * @return [type] [description]
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'Widgets', 'jet-woo-builder' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'jet-woo-builder-settings';
	}

	/**
	 * [get_page_link description]
	 * @return [type] [description]
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_style(
			'jet-woo-builder-admin-css',
			jet_woo_builder()->plugin_url( 'assets/css/admin.css' ),
			false,
			jet_woo_builder()->get_version()
		);

		wp_enqueue_script(
			'jet-woo-builder-admin-vue-components',
			jet_woo_builder()->plugin_url( 'assets/js/admin-vue-components.js' ),
			array( 'cx-vue-ui' ),
			jet_woo_builder()->get_version(),
			true
		);

		wp_localize_script(
			'jet-woo-builder-admin-vue-components',
			'jetWooBuilderSettingsConfig',
			apply_filters( 'jet-woo-builder/admin/settings-page/localized-config', jet_woo_builder_settings()->get_localize_data() )
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['jet-woo-builder-avaliable-addons'] = jet_woo_builder()->plugin_path( 'templates/admin-templates/avaliable-addons-settings.php' );

		return $templates;
	}
}
