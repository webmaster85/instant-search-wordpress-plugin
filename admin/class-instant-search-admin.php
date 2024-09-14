<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.marincas.net
 * @since      1.0.0
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/admin
 * @author     webmaster85 <weszty@wesztyweb.com>
 */
class Instant_Search_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('wp_ajax_nopriv_instant_search', [ $this,'instant_search_ajax' ] ); 
		add_action('wp_ajax_instant_search', [ $this,'instant_search_ajax' ] );
		add_action('admin_menu', [ $this,'instant_search_menu' ] );
		add_filter( 'posts_search', [ $this,'search_by_sku' ], 999, 2 ); 
		add_action('admin_post_flush_search_queries_action', [ $this,'flush_search_queries' ] );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/instant-search-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/instant-search-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function instant_search_ajax() {
		global $wpdb;
		$post_types = get_option('instant_search_post_types', array('post', 'page'));
		$query = ''; 
		if (isset($_POST['query'])) {
			$query = sanitize_text_field($_POST['query']); 
		}
		$min_length = 3;
		if (strlen($query) < $min_length) {
			echo '<h2>' . esc_html__('Please enter at least ' . $min_length . ' characters.') . '</h2>';
			die(); 
		}
		$this->log_search_query($query); 
		$results_per_page = get_option('instant_search_results_per_page', 10); 
		$search_query = new WP_Query(array(
			's' => $query,
			'post_type' => $post_types,
			'posts_per_page' => $results_per_page,
			'suppress_filters' => true		
		));
		if ($search_query->have_posts()) {
			while ($search_query->have_posts()) {
				$search_query->the_post();
				$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
				echo '<div class="search-result">';
				if ($thumbnail) {
					echo '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr(get_the_title()) . '">';
				}
				echo '<h2><a href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>';
				echo '<p>' . esc_html(substr(get_the_excerpt(), 0, 50)) . '</p>';
				echo '</div>';
			}
			echo '<div class="view-all"><a href="' . esc_url(home_url('/?s=' . urlencode($query))) . '" class="button">View All</a></div>';
		} else {
			echo '<h2>' . esc_html__('No results found.') . '</h2>';
		}
		wp_reset_postdata(); 
		die();
	}

	public function search_by_sku( $search, $query_vars ) {
		global $wpdb;
		if(isset($query_vars->query['s']) && !empty($query_vars->query['s'])){
			$args = array(
				'posts_per_page'  => -1,
				'post_type'       => 'product',
				'meta_query' => array(
					array(
						'key' => '_sku',
						'value' => $query_vars->query['s'],
						'compare' => 'LIKE' 
					)
				)
			);
			$posts = get_posts($args);
			if(empty($posts)) return $search; 
			$get_post_ids = array();
			foreach($posts as $post){
				$get_post_ids[] = $post->ID;  
			}
			if(sizeof( $get_post_ids ) > 0 ) {
					$search = str_replace( 'AND (((', "AND ((({$wpdb->posts}.ID IN (" . implode( ',', $get_post_ids ) . ")) OR (", $search); 
			}
		}
		return $search; 
	}

	public function flush_search_queries() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'instant_search_queries';
		$wpdb->query("TRUNCATE TABLE $table_name"); 
		wp_redirect(admin_url('admin.php?page=instant_search'));
		exit;
	}

	public function log_search_query($query) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'instant_search_queries';
		$query = sanitize_text_field($query);
		if (empty($query)) {
			return; 
		}
		$existing_query = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE query = %s", $query));
	
		if ($existing_query) {
			$wpdb->update(
				$table_name,
				array('count' => $existing_query->count + 1),
				array('id' => $existing_query->id)
			);
		} else {
			$wpdb->insert(
				$table_name,
				array('query' => $query, 'count' => 1) 
			);
		}
	}

	public function get_top_searches($limit = 20) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'instant_search_queries';
	
		return $wpdb->get_results($wpdb->prepare("SELECT query, count FROM $table_name ORDER BY count DESC LIMIT %d", $limit));
	}

	public function instant_search_menu() {
		add_menu_page('Instant Search Settings', 'Instant Search', 'manage_options', 'instant_search', [ $this, 'instant_search_options' ], 'dashicons-search', 20);
	}

	public function instant_search_options() {	
		if (isset($_POST['save_changes'])) {
			if (!isset($_POST['instant_search_nonce']) || !wp_verify_nonce($_POST['instant_search_nonce'], 'instant_search_save')) {
				echo '<div class="error"><p>' . esc_html__('Nonce verification failed. Please try again.', 'text-domain') . '</p></div>';
				return;
			}
			echo '<div class="updated"><p>' . esc_html__('Settings saved successfully.', 'text-domain') . '</p></div>';
		}	
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.')); 
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['post_types'])) {
				$post_types = array_map('sanitize_text_field', $_POST['post_types']);
				update_option('instant_search_post_types', $post_types);
			}
			if (isset($_POST['display_style'])) {
				$display_style = sanitize_text_field($_POST['display_style']);
				update_option('instant_search_display_style', $display_style);
			}
			if (isset($_POST['search_placeholder'])) {
				$search_placeholder = sanitize_text_field($_POST['search_placeholder']);
				update_option('instant_search_placeholder', $search_placeholder);
			}
			if (isset($_POST['search_form_width'])) {
				$search_form_width = sanitize_text_field($_POST['search_form_width']);
				update_option('instant_search_form_width', $search_form_width);
			}
			if (isset($_POST['search_form_width2'])) {
				$search_form_width2 = sanitize_text_field($_POST['search_form_width2']);
				update_option('instant_search_form_width2', $search_form_width2);
			}
			 if (isset($_POST['search_method'])) {
				$search_method = sanitize_text_field($_POST['search_method']);
				update_option('instant_search_method', $search_method);
			}
			if (isset($_POST['enable_voice_search'])) {
				update_option('instant_search_enable_voice_search', '1'); 
			} else {
				update_option('instant_search_enable_voice_search', '0'); 
			}
			if (isset($_POST['results_per_page'])) {
				$results_per_page = sanitize_text_field($_POST['results_per_page']);
				update_option('instant_search_results_per_page', $results_per_page); 
			}
		if (isset($_POST['flush_search_queries'])) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'instant_search_queries';
			$wpdb->query("TRUNCATE TABLE $table_name"); 
			echo '<div class="updated"><p>' . esc_html__('Search queries have been flushed.') . '</p></div>';
		}		
		}
		$post_types = get_option('instant_search_post_types', array('post', 'page'));
		$display_style = get_option('instant_search_display_style', 'list');
		$search_placeholder = get_option('instant_search_placeholder', 'What are we searching for today?');
		$all_post_types = get_post_types(array('public' => true), 'names');
		$search_form_width = get_option('instant_search_form_width', '50%');
		$search_form_width2 = get_option('instant_search_form_width2', '50%');
		$search_method = get_option('instant_search_method', 'overlay'); 
		$enable_voice_search = get_option('instant_search_enable_voice_search', '1');
		$results_per_page = get_option('instant_search_results_per_page', '10'); 
		$top_searches = $this->get_top_searches(20); 	
		
		require 'partials/instant-search-admin-display.php';
	} 
}
