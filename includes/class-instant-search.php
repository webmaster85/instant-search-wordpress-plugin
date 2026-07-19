<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Instant_Search {  
    private static $instance = null;  
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }   
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }   
    private function define_constants() {        
    }
    private function includes() {
        require_once INSTANT_SEARCH_PATH . 'includes/class-instant-search-rest.php';
        new Instant_Search_REST();
        if ( is_admin() ) {
            require_once INSTANT_SEARCH_PATH . 'includes/class-instant-search-admin.php';
            new Instant_Search_Admin();
        }
    } 
    private function init_hooks() {
        register_deactivation_hook( INSTANT_SEARCH_PATH . 'instant-search.php', [ $this, 'deactivate' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );   
        add_action( 'init', [ 'Instant_Search_DB', 'check_table' ] );
       add_action( 'wp_head', [ $this, 'output_custom_styles' ] );
    } 
    public function activate() {       
    } 
    public function deactivate() {       
    }  
    public function register_shortcodes() {
        add_shortcode( 'instant_search', [ $this, 'render_search_form' ] );
    }
    public function render_search_form() {
        ob_start();
        include INSTANT_SEARCH_PATH . 'templates/searchform.php';
        return ob_get_clean();
    }
    public function enqueue_scripts() {
        wp_enqueue_script( 'jquery' );   
        wp_enqueue_script(
            'instant_search',
            INSTANT_SEARCH_URL . 'assets/js/instant_search.js',
            [ 'jquery' ],
            INSTANT_SEARCH_VERSION,
            true
        );  
        wp_enqueue_style(
            'instant_search',
            INSTANT_SEARCH_URL . 'assets/css/instant_search.css',
            [],
            INSTANT_SEARCH_VERSION
        );   
        $fa_css_path = INSTANT_SEARCH_PATH . 'assets/vendor/fontawesome/css/all.min.css';
        if ( file_exists( $fa_css_path ) ) {
            wp_enqueue_style(
                'instant_search-fa',
                INSTANT_SEARCH_URL . 'assets/vendor/fontawesome/css/all.min.css',
                [],
                '5.15.1' 
            );
        }
        wp_localize_script(
            'instant_search',
            'instant_search',
            [
                'endpoint_url'  => home_url( '/wp-json/instant-search/v1/search' ),
                'display_style' => get_option( 'instant_search_display_style', 'list' ),
                'search_method' => get_option( 'instant_search_method', 'overlay' ),
            ]
        );		 
    $this->output_custom_styles();		
    }	
	public function output_custom_styles() {
    $overlay_w = get_option( 'instant_search_form_width', '300px' );
    $inline_w  = get_option( 'instant_search_form_width2', '300px' );
    $overlay_w = $this->sanitize_css_width( $overlay_w, '300px' );
    $inline_w  = $this->sanitize_css_width( $inline_w,  '300px' );
    echo '<style id="instant-search-dynamic-css">
/* ===== INLINE (header) ===== */
/* Make the WRAPPER the containing block and the width source */
html body form#searchform2 .search-wrapper2{
    position:relative !important;
    width:' . esc_attr( $inline_w ) . ' !important;
    display:inline-block !important;
}
/* Make the input fill the wrapper width */
html body form#searchform2 .search-wrapper2 input#s2{
    width:100% !important;
    min-width:100% !important;
}
/* Now the results panel will match the wrapper width */
html body form#searchform2 .search-wrapper2 #inline-search-results{
    position:absolute !important;
    left:0 !important;
    width:100% !important;  /* 100% of .search-wrapper2 */
}

/* ===== OVERLAY (modal) ===== */
/* Same idea for overlay: use .search-wrapper as the source */
html body form#searchform .search-wrapper{
    position:relative !important;
    width:' . esc_attr( $overlay_w ) . ' !important;
    display:inline-block !important;
}
html body form#searchform .search-wrapper input#s{
    width:100% !important;
    min-width:100% !important;
}
html body form#searchform .search-wrapper #search-results{
    position:absolute !important;
    left:0 !important;
    width:100% !important;
}
    </style>';
}

    private function sanitize_css_width( $value, $fallback ) {
        $value = (string) $value;
        $value = trim( $value );
        if ( preg_match( '/^\d+(\.\d+)?(px|%)$/', $value ) ) {
            return $value;
        }
        return $fallback;
    }
}