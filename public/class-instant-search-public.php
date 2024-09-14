<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://www.marincas.net
 * @since      1.0.0
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/public
 * @author     webmaster85 <weszty@wesztyweb.com>
 */
class Instant_Search_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('wp_head', [ $this,'instant_search_custom_styles' ] );
		add_shortcode('instant_search', [ $this, 'instant_search_form' ] ); 
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Instant_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Instant_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/instant-search-public.css', array(), $this->version, 'all' );
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Instant_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Instant_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery'); 
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/instant-search-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'instant_search', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'display_style' => get_option('instant_search_display_style', 'list'),
			'search_method' => get_option('instant_search_method', 'overlay')
		));
	}

	public function instant_search_custom_styles() {
		$search_form_width = get_option('instant_search_form_width', '300px'); 
		$search_form_width2 = get_option('instant_search_form_width2', '300px'); 
		echo '<style>
			.search-wrapper {
				width: ' . esc_attr($search_form_width) . '; 
			}
			.search-wrapper2, input#s2 {
				width: ' . esc_attr($search_form_width2) . ';
			}
			
		</style>';
	}

	public function instant_search_form() {
		ob_start();
			require 'partials/instant-search-public-display.php';
		$form = ob_get_clean(); 
		return $form; 
	}
}
