<?php

/**
 * The plugin core
 *
 * This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.marincas.net
 * @since             1.0.0
 * @package           Instant_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Instant Search
 * Plugin URI:        https://https://instant-search.net
 * Description:       A WordPress search plugin with live and voice search.
 * Version:           1.0.0
 * Author:            webmaster85
 * Author URI:        https://www.marincas.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       instant-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'INSTANT_SEARCH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-instant-search-activator.php
 */
function activate_instant_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instant-search-activator.php';
	Instant_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-instant-search-deactivator.php
 */
function deactivate_instant_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instant-search-deactivator.php';
	Instant_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_instant_search' );
register_deactivation_hook( __FILE__, 'deactivate_instant_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-instant-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_instant_search() {

	$plugin = new Instant_Search();
	$plugin->run();

}
run_instant_search();