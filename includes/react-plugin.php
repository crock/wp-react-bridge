<?php
/**
 * Plugin Name:     WordPress React Create App
 * Description:     Load React app in WorePress Admin concept
 * Author:          Ben Broide
 * Text Domain:     react-plugin
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         React_Plugin
 */

/**
 * Class RpLoadReactApp.
 */
class RpLoadReactApp {

	/**
	 * @var string
	 */
	private $selector = '';
	/**
	 * @var string
	 */
	private $limit_load_hook = '';
	/**
	 * @var bool|string
	 */
	private $limit_callback = '';

	/**
	 * RpLoadReactApp constructor.
	 *
	 * @param string $enqueue_hook Hook to enqueue scripts.
	 * @param string $limit_load_hook Limit load to hook in admin load. If front end pass empty string.
	 * @param bool|string $limit_callback Limit load by callback result. If back end send false.
	 * @param string $css_selector Css selector to render app.
	 */
	function __construct( $enqueue_hook, $limit_load_hook,$limit_callback = false, $css_selector)  {
		$this->selector = $css_selector;
		$this->limit_load_hook = $limit_load_hook;
		$this->limit_callback= $limit_callback;

		add_action( $enqueue_hook, [$this,'load_react_app']);
	}

	/**
	 * Load react app files in WordPress admin.
	 *
	 * @param $hook
	 *
	 * @return bool|void
	 */
	function load_react_app( $hook ) {
		// Limit app load in admin by admin page hook.
		$is_main_dashboard = $hook === $this->limit_load_hook;
		if ( ! $is_main_dashboard && is_bool($this->limit_callback))
			return;

		// Limit app load in front end by callback.
		$limit_callback = $this->limit_callback;
		if(is_string($limit_callback) && !$limit_callback()  )
			return;

		// Get assets links.
		$assets_files = $this->get_assets_files();

		$js_files  = array_filter( $assets_files,  fn($file_string) => pathinfo( $file_string, PATHINFO_EXTENSION ) === 'js');
		$css_files  = array_filter( $assets_files,  fn($file_string) => pathinfo( $file_string, PATHINFO_EXTENSION ) === 'css');

		// Load css files.
		foreach ( $css_files as $index => $css_file ) {
			wp_enqueue_style( 'react-plugin-' . $index, RP_REACT_APP_BUILD . $css_file );
		}

		// Load js files.
		foreach ( $js_files as $index => $js_file ) {
			wp_enqueue_script( 'react-plugin-' . $index, RP_REACT_APP_BUILD . $js_file, array(), RP_PLUGIN_VERSION, true );
		}

		// Variables for app use - These variables will be available in window.rpReactPlugin variable.
		wp_localize_script( 'react-plugin-0', 'rpReactPlugin',
			array( 'appSelector' => $this->selector )
		);

		wp_localize_script( 'wp-api', 'wpApiSettings', array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' )
		) );
	}

	/**
	 * Get app entry points assets files.
	 *
	 * @return bool|void
	 */
	private function get_assets_files(){
		// Request manifest file.
		$request = file_get_contents( RP_MANIFEST_URL );

		// If the remote request fails.
		if ( !$request  )
			return false;

		// Convert json to php array.
		$files_data = json_decode( $request );
		if ( $files_data === null )
			return;

		// No entry points found.
		if ( ! property_exists( $files_data, 'entrypoints' ) )
			return false;

		return $files_data->entrypoints;
	}
}