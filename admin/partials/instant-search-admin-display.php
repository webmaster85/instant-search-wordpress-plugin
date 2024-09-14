<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.marincas.net
 * @since      1.0.0
 *
 * @package    Instant_Search
 * @subpackage Instant_Search/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrapper">
    <h1 class="bigtitle">Instant Search Plugin Settings <button id="toggle-ads-button">Show Ads</button></h1>	
    <div id="toggle-ads">
            <script>
            jQuery(document).ready(function() {
                jQuery("#toggle-ads-button").click(function() {
                    jQuery("#toggle-ads").toggle();
                    if (jQuery("#toggle-ads").is(":visible")) {
                        jQuery("#toggle-ads-button").text("Hide Ads").css("background-color", "transparent");;
                    } else {
                        jQuery("#toggle-ads-button").text("Show Ads").css("background-color", "transparent");
                    }
                });
            });	
            </script>				
            <div class="ads-images">	
            <h3>Want to create stunning websites with the assistance of AI?</h3>
            <a href="https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=47480" target="_blank" rel="nofollow">
            <img style="border:0px" src="<?php echo esc_url(plugins_url('../images/banners/et-affiliate.png', __FILE__)); ?>" width="570" height="100" alt="">
            </a>		
            <h3>Check out the best E-Commerce theme:</h3>
            <a href="https://themeforest.net/item/rey-multipurpose-woocommerce-theme/24689383" target="_blank" rel="nofollow">
            <img style="border:0px" src="<?php echo esc_url(plugins_url('../images/banners/rey.png', __FILE__)); ?>" width="570" height="100" alt="">
            </a>
            <h3>Get great hosting:</h3>
            <a href="https://hosterion.ro/client/aff.php?aff=160" target="_blank" rel="nofollow">
            <img style="border:0px" src="<?php echo esc_url(plugins_url('../images/banners/hosterion-affiliate.png', __FILE__)); ?>" width="570" height="100" alt="">
            </a>	
            </div>		
        </div>				
        <form method="post" action="">  
        <?php wp_nonce_field('instant_search_save', 'instant_search_nonce'); ?>
        <div class="settings-wrapper">	  
    <script>		
        document.addEventListener('click', function (e) {
            e = e || window.event;
            var target = e.target || e.srcElement;

            if (target.hasAttribute('data-toggle') && target.getAttribute('data-toggle') == 'modal_settings') {
                if (target.hasAttribute('data-target')) {
                    var m_ID = target.getAttribute('data-target');
                    document.getElementById(m_ID).classList.add('open_settings');
                    e.preventDefault();
                }
            }
            if ((target.hasAttribute('data-dismiss') && target.getAttribute('data-dismiss') == 'modal_settings') || target.classList.contains('modal_settings')) {
                var modal = document.querySelector('[class="modal_settings open_settings"]');
                modal.classList.remove('open_settings');
                e.preventDefault();
            }
        }, false);
    </script>		
    <script>
        function confirmFlush() {
        if (confirm('Are you sure you want to flush the search queries? This action cannot be undone.')) {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = '<?php echo esc_url(admin_url('admin-post.php')); ?>';
            var inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'flush_search_queries_action';
            form.appendChild(inputAction);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>	
        <div class="is-box">
            <div class="is-title">
                <h3>Search method
                <span data-target="search_method_settings" data-toggle="modal_settings">&#63;</span>
                </h3> 
                </div> 
            <div class="is-content">     
                <select style="max-width:100%;" name="search_method">
                <option value="overlay"<?php if ($search_method == 'overlay') echo ' selected'; ?>>Overlay</option>
                <option value="inline"<?php if ($search_method == 'inline') echo ' selected'; ?>>Inline</option>
                </select>
            </div>
        </div>
        <div id="search_method_settings" class="modal_settings">
            <div class="modal-window">			
                <h3>Search method</h3>
                <p>Select how do you want the form to behave, inline to display the search results right below the form, or overlay to display them in a large modal, all over the screen. When the overlay method is selected, the live voice search microphone icon will disappear from the inline form, and be present just in the overlay modal.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>
            <div class="is-box">
                <div class="is-title">
                    <h3>Display
                    <span data-target="display_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div>
                <div class="is-content" style="text-align:left;">     
                    <select style="max-width:100%;" name="display_style">
                        <option value="list"<?php if ($display_style == 'list') echo ' selected'; ?>>List</option>
                        <option value="grid"<?php if ($display_style == 'grid') echo ' selected'; ?>>Grid</option>
                    </select>	
                </div> 
            </div>
        <div id="display_settings" class="modal_settings">
            <div class="modal-window">			
                <h3>Display settings</h3>
                <p>The grid is perfect for desktops, offering a visually appealing layout, while the list view is ideal for small screens like phones, ensuring better readability and easier navigation.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>
        <div class="is-box">
            <div class="is-title">
                <h3>Number of results
                    <span data-target="results_per_page_settings" data-toggle="modal_settings">&#63;</span>
                </h3>
            </div>
            <div class="is-content" style="text-align:left;">                 
        <input type="text" style="min-width:250px;" name="results_per_page" value="<?php echo esc_attr($results_per_page); ?>" placeholder="e.g., 5, 10" min="1" />		
            </div>
        </div>		
        <div id="results_per_page_settings" class="modal_settings">
            <div class="modal-window">			
                <h3>Number of results</h3>
                <p>Control the number of AJAX live search results. If there are more results than the number set here, a "View All" button will be displayed to redirect the visitors to the search results page. Leave blank for all. Keep in mind this number is also controlled by WordPress in <strong>Settings -> Reading -> Syndication feeds</strong>.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>		
            <div class="is-box">
            <div class="is-title">
                <h3>Voice Search
                    <span data-target="voice_search_settings" data-toggle="modal_settings">&#63;</span>
                </h3>
            </div>
            <div class="is-content" style="text-align:left;">
                <label>
                    <input type="checkbox" name="enable_voice_search" value="1" <?php checked($enable_voice_search, '1'); ?> />
                    Enabled
                </label>
            </div>
        </div>			
            <div id="voice_search_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>Voice search</h3>
                <p>
                Enable or disable the voice search microphone icon.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>			
        <div class="is-box">
            <div class="is-title">
                <h3>Inline width
                <span data-target="inline_width_settings" data-toggle="modal_settings">&#63;</span>
                </h3>
            </div> 
            <div class="is-content" style="text-align:left;">  	
            <input type="text" style="min-width:250px;" name="search_form_width2" value="<?php echo esc_attr($search_form_width2); ?>" placeholder="e.g., 50%, 300px, 30vh" />	
            </div>  
        </div>			
        <div id="inline_width_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>Inline width</h3>
                <p>
                Customize the width of the inline form. By default it's set to 300px, but you can change it to any CSS unit that suits your needs. You could use 50% for a responsive design, 300px for a fixed width, or 30vh to make it proportional to the viewport height. The inline form will span as long as the wrapper where the shortcode is placed allows it. For example, if you're using page builders like Divi Builder or Elementor, if the shortcode is placed in a 2/3 column, the search results will be displayed in a narrow space if this setting is in percentage units. However, you can override that space with a width set in pixels.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>	
                <div class="is-box">
                <div class="is-title">
                    <h3>Overlay width
                    <span data-target="overlay_width_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div> 
                <div class="is-content" style="text-align:left;">     	
            <input type="text" style="min-width:250px;" name="search_form_width" value="<?php echo esc_attr($search_form_width); ?>" placeholder="e.g., 50%, 300px, 30vh" />		
                </div>
            </div>		
            <div id="overlay_width_settings" class="modal_settings">
                <div class="modal-window">	
                    <h3>Overlay width</h3>
                    <p>
                    Customize the width of the overlay form. By default it's set to 300px, but you can change it to any CSS unit that suits your needs. You could use 50% for a responsive design, 300px for a fixed width, or 30vh to make it proportional to the viewport height.
                    </p>
                    <button class="close_settings" data-dismiss="modal_settings">Close</button>
                </div>
            </div>
        <div class="is-box">
                <div class="is-title">
                    <h3>Placeholder text
                    <span data-target="placeholder_text_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div>
                <div class="is-content" style="text-align:left;">       
                    <input type="text" style="min-width:250px;" name="search_placeholder" value="<?php echo esc_attr($search_placeholder); ?>" />
                </div>
            </div>
        <div id="placeholder_text_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>Placeholder text</h3>
                <p>
                Set a personalized message that will be displayed inside the search form. The default is usually "Search..." but you have the flexibility to change it. For example, you could use "What are we searching for today?" or anything else that suits your needs.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>
            <div class="is-box">
                <div class="is-title">
                    <h3>Post types
                    <span data-target="post_types_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div>
                <div class="is-content" style="text-align:left;">     
                    <?php foreach ($all_post_types as $post_type): ?>
                        <input type="checkbox" name="post_types[]" value="<?php echo esc_attr($post_type); ?>"<?php if (in_array($post_type, $post_types)) echo ' checked'; ?> /> <?php echo esc_html($post_type); ?><br />
                    <?php endforeach; ?>
                </div>
            </div>
        <div id="post_types_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>Post types</h3>
                <p>
                Choose the types of posts you'd like the form to display results for. Feel free to select multiple options.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>
            <div class="is-box">
                <div class="is-title">
                    <h3>Analytics <span class="notify-badge">NEW - BETA</span>
                    <span data-target="analytics_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div>
                <div class="is-content" style="text-align:left;">     				
                <span class="is-view-analytics" data-target="analytics_settings2" data-toggle="modal_settings">View</span>
                </div>
            </div>
        <div id="analytics_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>Analytics</h3>			
            <h2>Top 20 Searched Words On Your Website</h2>
            <p>
            This option creates a table in your WordPress database, called <a target="_blank" href="<?php echo esc_url(plugins_url('../images/banners/database_queries_table.jpg', __FILE__)); ?>"><i>instant_search_queries</i></a>
            (don't worry about this if you're not tech savvyy, it's really not important). It logs top 20 words your visitors search the most on your website and the entries are rewritten, so no need to worry about the table getting bloated in time. So for example let's say your website sells shoes but your visitors search the most after the word "dress", a product that doesn't exist on your website. They don't see any results, but you know they search for dresses because the word shows up here in most searched, so you might want to add that product.
            Be sure to specify this in your website's terms, visitors need to know their searches might be stored to improve the search experience. It's in beta tests, currently working to optimize the results.
            </p>		
                <button class="close_settings" data-dismiss="modal_settings">Close</button>		
            </div>
        </div>		
            <div id="analytics_settings2" class="modal_settings">
            <div class="modal-window">	
                <h3>Analytics</h3>			
            <h2>Top 20 Searched Words On Your Website</h2>		 
            <ul>
                <?php foreach ($top_searches as $search) : ?>
                    <li><?php echo esc_html($search->query) . ' - ' . esc_html($search->count); ?> times</li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="donate-button" id="flush-search-queries" onclick="confirmFlush()">Clear the list</button>		
                <button class="close_settings" data-dismiss="modal_settings">Close</button>		
            </div>
        </div>
        <div class="is-box">
            <div class="is-title">
                <h3>Shortcode
                <span data-target="shortcode_settings" data-toggle="modal_settings">&#63;</span>
                </h3>
            </div>
            <div class="is-content" style="text-align:left;">      
            <h3>[instant_search]</h3>				
            </div>
        </div>		
        <div id="shortcode_settings" class="modal_settings">
            <div class="modal-window">	
                <h3>The shortcode</h3>
                <p>
                Use the shortcode to display a search form anywhere. Personally, I find it works best when placed in the website's header. It optimizes user experience and navigation, as it's a prime location for visitors to initiate searches, thus enhancing overall site usability.
                </p>
                <button class="close_settings" data-dismiss="modal_settings">Close</button>
            </div>
        </div>
                <div class="is-box">
                <div class="is-title">
                    <h3>Donate
                    <span data-target="donate_settings" data-toggle="modal_settings">&#63;</span>
                    </h3>
                </div>
                <div class="is-content" style="text-align:left;">       
                <h3><a class="donate-button" href="https://instant-search.net/#donate" target="_blank">Donate</a></h3>		
                </div>
            </div> 					
            <div id="donate_settings" class="modal_settings">
                <div class="modal-window">	
                    <h3>Donate</h3>
                    <p>
                    I use the donations to improve the plugin. That could mean a variety of things. I mainly buy web development courses that let me upgrade my skills to understand the whole environment better, how the search works in-depth, and see the big picture.
                    </p>
                    <button class="close_settings" data-dismiss="modal_settings">Close</button>
                </div>
            </div>	
        <h3>
            Test <a href="https://instant-search.net/live-voice-search/" target="_blank"> live voice search </a> and <a href="https://instant-search.net/live-search/" target="_blank"> live search</a>.
        </h3>		 
        </div> 	
        <p class="submit-changes">
            <input type="submit" class="save-changes" value="Save Changes" />
        </p>		 
        </form>   
</div>