<?php
/*
Plugin Name: WP React Bridge
Plugin URI: https://github.com/crock/wp-react-bridge
Description: Replace a WordPress plugin's settings page with a full React app
Author: Alex Crocker
Version: 1.0.0
Author URI: https://crock.dev
Text Domain: wp-react-bridge
GitHub Plugin URI: https://github.com/crock/wp-react-bridge
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Setting react app path constants.
define('RP_PLUGIN_VERSION','1.0.0' );
define('RP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) . 'web/');
define('RP_REACT_APP_BUILD', RP_PLUGIN_DIR_URL . 'build/');
define('RP_MANIFEST_URL', RP_REACT_APP_BUILD . 'asset-manifest.json');

require(__DIR__ . '/includes/react-plugin.php');

/**
 * Calling the plugin class with parameters.
 */
function rp_load_plugin(){
	// Loading the app in WordPress admin main screen.
	new RpLoadReactApp('admin_enqueue_scripts', 'index.php', false, '#wpbody .wrap');
	// Loading the app WordPress front end page.
	// new RpLoadReactApp( 'wp_enqueue_scripts', '', 'is_front_page', '#site-footer');
}

add_action('init','rp_load_plugin');

?>