<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Instant_Search_Admin {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] ); 
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
        add_action( 'admin_post_flush_search_queries_action', [ $this, 'flush_search_queries' ] );
    }
    public function add_menu() {
        add_menu_page(
            __( 'Instant Search Settings', 'instant-search' ),
            __( 'Instant Search', 'instant-search' ),
            'manage_options',
            'instant_search',
            [ $this, 'render_settings_page' ],
            'dashicons-search',
            20
        );
    } 
    public function enqueue_admin_styles( $hook = '' ) {
        if ( $hook && false === strpos( $hook, 'instant_search' ) ) {
            return;
        }
        wp_enqueue_style(
            'instant_search_admin',
            INSTANT_SEARCH_URL . 'assets/css/admin.css',
            [],
            INSTANT_SEARCH_VERSION
        );
        wp_enqueue_script(
            'instant_search_admin',
            INSTANT_SEARCH_URL . 'assets/js/admin.js',
            [],
            INSTANT_SEARCH_VERSION,
            true
        );
        wp_localize_script(
            'instant_search_admin',
            'instantSearchAdmin',
            [
                'upgradeUrl' => 'https://www.marincas.net/instant-search/',
                'isPro'      => class_exists( 'Instant_Search_License' ) && Instant_Search_License::is_active() ? 1 : 0,
            ]
        );
    }
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'instant-search' ) );
        }
        if ( isset( $_POST['save_changes'] ) && check_admin_referer( 'instant_search_save', 'instant_search_nonce' ) ) {
            $this->save_settings();
            echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'instant-search' ) . '</p></div>';
        }
        if (
            isset( $_POST['flush_search_queries'], $_POST['instant_search_flush_nonce'] ) &&
            wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['instant_search_flush_nonce'] ) ), 'instant_search_flush' )
        ) {
            $this->flush_search_queries();
            echo '<div class="updated"><p>' . esc_html__( 'Search queries have been flushed.', 'instant-search' ) . '</p></div>';
        }
        $post_types          = get_option( 'instant_search_post_types', [ 'post', 'page' ] );
        $display_style       = get_option( 'instant_search_display_style', 'list' );
        $search_placeholder  = get_option( 'instant_search_placeholder', 'What are we searching for today?' );
        $all_post_types      = get_post_types( [ 'public' => true ], 'names' );
        $search_form_width   = get_option( 'instant_search_form_width', '50%' );
        $search_form_width2  = get_option( 'instant_search_form_width2', '50%' );
        $search_method       = get_option( 'instant_search_method', 'overlay' );
        $enable_voice_search = get_option( 'instant_search_enable_voice_search', '1' ); 
        $results_per_page    = get_option( 'instant_search_results_per_page', '10' );   
        $top_searches        = Instant_Search_DB::get_top_searches( 20 );
        $search_fields       = get_option( 'instant_search_search_fields', [ 'title', 'content', 'excerpt' ] );
        $search_by_sku       = get_option( 'instant_search_search_by_sku', '1' );   
        include INSTANT_SEARCH_PATH . 'templates/admin-settings.php';
    } 
    private function save_settings() {
        if (
            ! isset( $_POST['instant_search_nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['instant_search_nonce'] ) ),
                'instant_search_save'
            )
        ) {
            return;
        }
			$post_types_input = filter_input( INPUT_POST, 'post_types', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $post_types_input ) ) {
				$raw = array_map( 'sanitize_key', (array) wp_unslash( $post_types_input ) );
				$allowed = get_post_types( [ 'public' => true ], 'names' );
				$san     = array_values( array_intersect( $raw, $allowed ) );
				update_option( 'instant_search_post_types', $san );
			}
			if ( isset( $_POST['display_style'] ) ) {
				update_option(
                'instant_search_display_style',
                sanitize_text_field( wp_unslash( $_POST['display_style'] ) )
				);
			}

			if ( isset( $_POST['search_placeholder'] ) ) {
				update_option(
                'instant_search_placeholder',
                sanitize_text_field( wp_unslash( $_POST['search_placeholder'] ) )
            );
			}
			if ( isset( $_POST['search_form_width'] ) ) {
				update_option(
                'instant_search_form_width',
                sanitize_text_field( wp_unslash( $_POST['search_form_width'] ) )
            );
			}
			if ( isset( $_POST['search_form_width2'] ) ) {
				update_option(
                'instant_search_form_width2',
                sanitize_text_field( wp_unslash( $_POST['search_form_width2'] ) )
            );
			}
			if ( isset( $_POST['search_method'] ) ) {
				update_option(
                'instant_search_method',
                sanitize_text_field( wp_unslash( $_POST['search_method'] ) )
            );
			}
				update_option(
				'instant_search_enable_voice_search',
				isset( $_POST['enable_voice_search'] ) ? '1' : '0'
			);
			if ( isset( $_POST['results_per_page'] ) ) {
				$num = (int) sanitize_text_field( wp_unslash( $_POST['results_per_page'] ) );
            if ( $num < 1 ) {
                $num = 10;
            }
            update_option( 'instant_search_results_per_page', $num );
			}
        $allowed_fields       = [ 'title', 'content', 'excerpt' ];
        $search_fields_input  = filter_input( INPUT_POST, 'search_fields', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        if ( is_array( $search_fields_input ) ) {
            $raw_fields = array_map( 'sanitize_key', (array) wp_unslash( $search_fields_input ) );
            $san_fields = array_values( array_intersect( $raw_fields, $allowed_fields ) );
        } else {
            $san_fields = [];
        }
        update_option( 'instant_search_search_fields', $san_fields );
        update_option(
            'instant_search_search_by_sku',
            isset( $_POST['search_by_sku'] ) ? '1' : '0'
        );
    }
    public function flush_search_queries() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if (
            isset( $_POST['instant_search_flush_nonce'] ) &&
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['instant_search_flush_nonce'] ) ),
                'instant_search_flush'
            )
        ) {
            return;
        }
        Instant_Search_DB::flush_search_queries();
        $referer = wp_get_referer();
        if ( $referer ) {
            wp_safe_redirect( esc_url_raw( $referer ) );
        } else {
            wp_safe_redirect( admin_url( 'admin.php?page=instant_search' ) );
        }
        exit;
    }
}