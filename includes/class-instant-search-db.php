<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Instant_Search_DB {
    private static function table_name() {
        global $wpdb;
        $name = $wpdb->prefix . 'instant_search_queries';
        return preg_replace( '/[^A-Za-z0-9_]/', '', $name );
    } 
    public static function activate() {
        global $wpdb;
        $table_name      = self::table_name();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
            query VARCHAR(255) NOT NULL,
            count MEDIUMINT(9) NOT NULL DEFAULT 1,
            PRIMARY KEY  (id),
            KEY query_key (query)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql ); 
    }
    public static function log_search_query( $query ) {
        global $wpdb;
        $query = sanitize_text_field( $query );
        if ( '' === $query ) {
            return;
        }
        $exists_cache_key = 'exists_' . md5( $query );
        $existing         = wp_cache_get( $exists_cache_key, 'instant_search' );
        if ( false === $existing ) {    
            $existing = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT id, count FROM `{$wpdb->prefix}instant_search_queries` WHERE query = %s",
                    $query
                )
            );
            wp_cache_set( $exists_cache_key, $existing ? $existing : 0, 'instant_search', MINUTE_IN_SECONDS );
        } elseif ( 0 === $existing ) {
            $existing = null; 
        }
        if ( $existing ) {        
            $wpdb->update(
                self::table_name(),
                [ 'count' => (int) $existing->count + 1 ],
                [ 'id'    => (int) $existing->id ],
                [ '%d' ],
                [ '%d' ]
            );
        } else {         
            $wpdb->insert(
                self::table_name(),
                [ 'query' => $query, 'count' => 1 ],
                [ '%s', '%d' ]
            );
        }    
        wp_cache_delete( 'top_10',  'instant_search' );
        wp_cache_delete( 'top_20',  'instant_search' );
        wp_cache_delete( 'top_50',  'instant_search' );
        wp_cache_delete( $exists_cache_key, 'instant_search' );
    } 
    public static function get_top_searches( $limit = 20 ) {
        global $wpdb;
        $limit = max( 1, (int) $limit );     
        $cache_key = 'top_' . $limit;
        $cached    = wp_cache_get( $cache_key, 'instant_search' );
        if ( false !== $cached ) {
            return $cached;
        }   
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT query, count FROM `{$wpdb->prefix}instant_search_queries` ORDER BY count DESC LIMIT %d",
                $limit
            )
        );
        wp_cache_set( $cache_key, $results, 'instant_search', 5 * MINUTE_IN_SECONDS );
        return $results;
    }
    public static function flush_search_queries() {
        global $wpdb;
        $wpdb->query( "TRUNCATE TABLE `{$wpdb->prefix}instant_search_queries`" );
        wp_cache_delete( 'top_10', 'instant_search' );
        wp_cache_delete( 'top_20', 'instant_search' );
        wp_cache_delete( 'top_50', 'instant_search' );
    }
    public static function check_table() {
        global $wpdb;
        $exists = wp_cache_get( 'table_exists', 'instant_search' );
        if ( false === $exists ) {
            $table_name = self::table_name();   
            $exists = $wpdb->get_var(
                $wpdb->prepare(
                    'SHOW TABLES LIKE %s',
                    $table_name
                )
            );
            wp_cache_set( 'table_exists', $exists ? $exists : '', 'instant_search', 10 * MINUTE_IN_SECONDS );
        } elseif ( '' === $exists ) {
            $exists = null; 
        }
        if ( $exists !== self::table_name() ) {
            self::activate();
        }
    }
}