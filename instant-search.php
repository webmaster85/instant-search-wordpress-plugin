<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
?>
<?php
/**
 * Plugin Name: Instant Search – Turn Search Into Sales
 * Plugin URI: https://www.marincas.net/instant-search
 * Description: Live Ajax search for WordPress & WooCommerce. Visitors see results as they type and can add to cart directly from the search results.
 * Version: 1.1.8
 * Author: webmaster85
 * Author URI: https://www.marincas.net
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'INSTANT_SEARCH_PATH' ) ) {
    define( 'INSTANT_SEARCH_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'INSTANT_SEARCH_URL' ) ) {
    define( 'INSTANT_SEARCH_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'INSTANT_SEARCH_VERSION' ) ) {
    define( 'INSTANT_SEARCH_VERSION', '1.1.8' );
}

require_once INSTANT_SEARCH_PATH . 'includes/class-instant-search-db.php';
register_activation_hook( __FILE__, [ 'Instant_Search_DB', 'activate' ] );

require_once INSTANT_SEARCH_PATH . 'includes/class-instant-search.php';

add_action( 'plugins_loaded', [ 'Instant_Search', 'get_instance' ] );