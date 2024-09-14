<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.marincas.net
 * @since      1.0.0
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Instant_Search
 * @subpackage Instant_Search/includes
 * @author     webmaster85 <weszty@wesztyweb.com>
 */
class Instant_Search_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'instant_search_queries'; 
        $charset_collate = $wpdb->get_charset_collate();
        if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                query varchar(255) NOT NULL,
                count mediumint(9) NOT NULL DEFAULT 1,
                PRIMARY KEY  (id)
            ) $charset_collate;";
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql); 
        }
	}

}
