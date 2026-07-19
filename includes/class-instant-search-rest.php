<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Instant_Search_REST {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }
    public function register_routes() {
        register_rest_route( 'instant-search/v1', '/search', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'handle_search' ],
            'permission_callback' => '__return_true',
        ] );
    }
    public function handle_search( $request ) {
        global $wpdb;
        $query      = sanitize_text_field( (string) $request->get_param( 'query' ) );
        $post_types = get_option( 'instant_search_post_types', [ 'post', 'page' ] );
        $min_length = 3;
        if ( strlen( $query ) < $min_length ) {
            $message = sprintf( esc_html__( 'Please enter at least %d characters.', 'instant-search' ), $min_length );
            return new WP_REST_Response( '<h2>' . $message . '</h2>', 200 );
        }
        if ( method_exists( 'Instant_Search_DB', 'log_search_query' ) ) {
            Instant_Search_DB::log_search_query( $query );
        }
        $results_per_page = (int) get_option( 'instant_search_results_per_page', 10 );
        $search_fields = get_option( 'instant_search_search_fields', [ 'title', 'content', 'excerpt' ] );
        $all_fields    = [ 'title', 'content', 'excerpt' ];
        $active_fields = array_values( array_intersect( (array) $search_fields, $all_fields ) );
        if ( empty( $active_fields ) ) {
            return new WP_REST_Response( '<h2>' . esc_html__( 'No results found.', 'instant-search' ) . '</h2>', 200 );
        }
        if ( count( $active_fields ) < 3 ) {
            $like      = '%' . $wpdb->esc_like( $query ) . '%';
            $field_map = [
                'title'   => $wpdb->posts . '.post_title',
                'content' => $wpdb->posts . '.post_content',
                'excerpt' => $wpdb->posts . '.post_excerpt',
            ];
            add_filter(
                'posts_search',
                static function ( $search, $wp_query ) use ( $active_fields, $field_map, $wpdb, $like ) {
                    if ( ! $wp_query->get( 's' ) ) {
                        return $search;
                    }
                    $conditions = [];
                    foreach ( $active_fields as $field ) {
                        if ( isset( $field_map[ $field ] ) ) {
                            $conditions[] = $wpdb->prepare( '(' . $field_map[ $field ] . ' LIKE %s)', $like );
                        }
                    }
                    return empty( $conditions ) ? $search : ' AND (' . implode( ' OR ', $conditions ) . ')';
                },
                10,
                2
            );
        }
        $search_results = get_posts( [
            's'                => $query,
            'post_type'        => (array) $post_types,
            'posts_per_page'   => $results_per_page,
            'suppress_filters' => false,
        ] );
        $search_by_sku = get_option( 'instant_search_search_by_sku', '1' );
        if (
            '1' === $search_by_sku &&
            class_exists( 'WooCommerce' ) &&
            in_array( 'product', (array) $post_types, true )
        ) {
            $sku_results = get_posts( [
                'post_type'      => 'product',
                'posts_per_page' => $results_per_page,
                'meta_query'     => [
                    [
                        'key'     => '_sku',
                        'value'   => $query,
                        'compare' => 'LIKE',
                    ],
                ],
            ] );
            if ( ! empty( $sku_results ) ) {
                $existing_ids = array_column( $search_results, 'ID' );
                foreach ( $sku_results as $sku_post ) {
                    if ( ! in_array( $sku_post->ID, $existing_ids, true ) ) {
                        $search_results[] = $sku_post;
                        $existing_ids[]   = $sku_post->ID;
                    }
                }
            }
        }
        $output = '';
        if ( ! empty( $search_results ) ) {
            $wc_active = class_exists( 'WooCommerce' );
            foreach ( $search_results as $post ) {
                $permalink = get_permalink( $post );
                $title     = get_the_title( $post );
                $thumb     = get_the_post_thumbnail_url( $post->ID, 'medium' );
                $excerpt   = wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 24, '…' );
                $price_html = '';
                if ( $wc_active && 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
                    $product = wc_get_product( $post->ID );
                    if ( $product ) {
                        $price_html = $product->get_price_html();
                    }
                }
                $output .= '<a class="search-result" href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( $title ) . '">';
                if ( $thumb ) {
                    $output .= '<img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $title ) . '">';
                }
                $output .= '<div class="search-result-body">';
                $output .= '<h2>' . esc_html( $title ) . '</h2>';
                if ( '' !== $price_html ) {
                    $output .= '<span class="search-result-price">' . $price_html . '</span>';
                } else {
                    $output .= '<p>' . esc_html( $excerpt ) . '</p>';
                }
                $output .= '</div>';
                $output .= '</a>';
            }
            $output .= '<div class="view-all"><a href="' . esc_url( home_url( '/?s=' . urlencode( $query ) ) ) . '" class="button">' . esc_html__( 'View All', 'instant-search' ) . '</a></div>';
        } else {
            $output .= '<h2>' . esc_html__( 'No results found.', 'instant-search' ) . '</h2>';
        }
        return new WP_REST_Response( $output, 200 );
    }
}