<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$is_pro_active = false;
if ( class_exists( 'Instant_Search_License' ) ) {
    $is_pro_active = (bool) Instant_Search_License::is_active();
}
$plugin_label   = $is_pro_active ? 'Instant Search PRO' : 'Instant Search';
$version_label  = $is_pro_active ? 'PRO ' . INSTANT_SEARCH_VERSION : 'FREE ' . INSTANT_SEARCH_VERSION;
$upgrade_url    = 'https://www.marincas.net/instant-search/';
$docs_url       = 'https://www.marincas.net/instant-search/documentation/';
$support_url    = 'https://wordpress.org/support/plugin/instant-search/';
$is_woo_active  = class_exists( 'WooCommerce' );
$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
$valid_tabs = [ 'general', 'display', 'woocommerce', 'search-sources', 'analytics', 'advanced', 'license' ];
if ( ! in_array( $active_tab, $valid_tabs, true ) ) {
    $active_tab = 'general';
}
?>
<div class="is-app" data-is-pro="<?php echo $is_pro_active ? '1' : '0'; ?>">
    <header class="is-app-header">
        <div class="is-app-brand">
            <span class="is-app-logo" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22"><path fill="#fff" d="M21 21l-4.3-4.3M10 17a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" stroke="#fff" stroke-width="2.2" fill="none" stroke-linecap="round"/></svg>
            </span>
            <h1 class="is-app-title"><?php echo esc_html( $plugin_label ); ?></h1>
            <span class="is-version-badge <?php echo $is_pro_active ? 'is-badge-pro-active' : 'is-badge-free'; ?>">
                <?php echo esc_html( $version_label ); ?>
            </span>
        </div>
    </header>
    <nav class="is-tabs" role="tablist">
        <a href="#general"        class="is-tab" role="tab" data-tab="general">General</a>
        <a href="#display"        class="is-tab" role="tab" data-tab="display">Display</a>
        <a href="#woocommerce"    class="is-tab" role="tab" data-tab="woocommerce">WooCommerce</a>
        <a href="#search-sources" class="is-tab" role="tab" data-tab="search-sources">Search Sources</a>
        <a href="#analytics"      class="is-tab" role="tab" data-tab="analytics">Analytics</a>
        <a href="#advanced"       class="is-tab" role="tab" data-tab="advanced">Advanced</a>
        <a href="#license"        class="is-tab" role="tab" data-tab="license">License</a>
    </nav>
    <div class="is-app-body">
        <main class="is-app-main">
            <form method="post" action="" id="instant-search-form">
                <?php wp_nonce_field( 'instant_search_save', 'instant_search_nonce' ); ?>
                <section id="tab-general" class="is-tab-panel" data-panel="general">
                    <header class="is-panel-header">
                        <h2>General</h2>
                        <p>Core search behavior and appearance settings.</p>
                    </header>
                    <div class="is-card">
                        <div class="is-field">
                            <label class="is-field-label" for="search_method">Search method</label>
                            <div class="is-field-control">
                                <select name="search_method" id="search_method" class="is-input">
                                    <option value="inline"  <?php selected( $search_method, 'inline' ); ?>>Inline</option>
                                    <option value="overlay" <?php selected( $search_method, 'overlay' ); ?>>Overlay</option>
                                </select>
                                <p class="is-field-help">Choose the behavior of the search form.</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label" for="results_per_page">Number of results</label>
                            <div class="is-field-control">
                                <input type="number" min="1" max="100" name="results_per_page" id="results_per_page" class="is-input" value="<?php echo esc_attr( $results_per_page ); ?>" />
                                <p class="is-field-help">Set the maximum number of search results to display.</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label" for="enable_voice_search">Voice Search</label>
                            <div class="is-field-control">
                                <label class="is-checkbox">
                                    <input type="checkbox" name="enable_voice_search" id="enable_voice_search" value="1" <?php checked( $enable_voice_search, '1' ); ?> />
                                    <span>Enable voice search functionality</span>
                                </label>
                                <p class="is-field-help">Enables voice input in the search bar.</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label" for="search_form_width2">Inline width</label>
                            <div class="is-field-control">
                                <div class="is-input-with-suffix">
                                    <input type="text" name="search_form_width2" id="search_form_width2" class="is-input" value="<?php echo esc_attr( $search_form_width2 ); ?>" placeholder="e.g., 300px or 50%" />
                                </div>
                                <p class="is-field-help">Set the width of the inline search bar (px or %).</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label" for="search_form_width">Overlay width</label>
                            <div class="is-field-control">
                                <div class="is-input-with-suffix">
                                    <input type="text" name="search_form_width" id="search_form_width" class="is-input" value="<?php echo esc_attr( $search_form_width ); ?>" placeholder="e.g., 600px or 80%" />
                                </div>
                                <p class="is-field-help">Set the width of the overlay search window (px or %).</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label" for="search_placeholder">Placeholder text</label>
                            <div class="is-field-control">
                                <input type="text" name="search_placeholder" id="search_placeholder" class="is-input" value="<?php echo esc_attr( $search_placeholder ); ?>" />
                                <p class="is-field-help">Customize the placeholder text shown in the search bar.</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label">Post types</label>
                            <div class="is-field-control">
                                <div class="is-checkbox-grid">
                                    <?php foreach ( $all_post_types as $post_type ) :
                                        $pt_obj   = get_post_type_object( $post_type );
                                        $pt_label = $pt_obj ? $pt_obj->labels->singular_name : $post_type;
                                        $pt_count = wp_count_posts( $post_type );
                                        $pt_total = isset( $pt_count->publish ) ? (int) $pt_count->publish : 0;
                                    ?>
                                        <label class="is-checkbox is-checkbox-row">
                                            <input type="checkbox" name="post_types[]" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( in_array( $post_type, (array) $post_types, true ) ); ?> />
                                            <span class="is-checkbox-label"><?php echo esc_html( $pt_label ); ?></span>
                                            <span class="is-checkbox-meta"><?php echo esc_html( $pt_total ); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="is-field-help">Choose which post types appear in search results.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-display" class="is-tab-panel" data-panel="display">
                    <header class="is-panel-header">
                        <h2>Display &amp; Layout</h2>
                        <p>Fine-tune how each search result looks.</p>
                    </header>
                    <div class="is-card">
                        <div class="is-field">
                            <label class="is-field-label">Layout style</label>
                            <div class="is-field-control">
                                <div class="is-layout-grid">
                                    <label class="is-layout-card <?php echo ( 'list' === $display_style ) ? 'is-layout-active' : ''; ?>">
                                        <input type="radio" name="display_style" value="list" <?php checked( $display_style, 'list' ); ?> />
                                        <span class="is-layout-name">List</span>
                                        <span class="is-layout-meta">FREE</span>
                                    </label>
                                    <label class="is-layout-card <?php echo ( 'grid' === $display_style ) ? 'is-layout-active' : ''; ?>">
                                        <input type="radio" name="display_style" value="grid" <?php checked( $display_style, 'grid' ); ?> />
                                        <span class="is-layout-name">Grid</span>
                                        <span class="is-layout-meta">FREE</span>
                                    </label>
                                    <label class="is-layout-card is-layout-pro-locked">
                                        <input type="radio" name="display_style" value="compact" disabled />
                                        <span class="is-layout-name">Compact</span>
                                        <span class="is-layout-meta is-layout-meta-pro">PRO</span>
                                    </label>
                                    <label class="is-layout-card is-layout-pro-locked">
                                        <input type="radio" name="display_style" value="big-cards" disabled />
                                        <span class="is-layout-name">Big Cards</span>
                                        <span class="is-layout-meta is-layout-meta-pro">PRO</span>
                                    </label>
                                    <label class="is-layout-card is-layout-pro-locked">
                                        <input type="radio" name="display_style" value="masonry" disabled />
                                        <span class="is-layout-name">Masonry</span>
                                        <span class="is-layout-meta is-layout-meta-pro">PRO</span>
                                    </label>
                                </div>
                                <p class="is-field-help">List & Grid are the classics. Compact is dense, Big Cards is image-forward, and Masonry is a Pinterest-style mosaic.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Image aspect ratio <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <select class="is-input" disabled>
                                    <option>1:1 (Square)</option>
                                    <option>4:3</option>
                                    <option>16:9</option>
                                    <option>Original</option>
                                </select>
                                <p class="is-field-help">Force consistent thumbnails across results so cards never jump around.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Excerpt length <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <input type="number" class="is-input" value="24" disabled />
                                <p class="is-field-help">Number of words to show under each result title. Set to 0 to hide the excerpt.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Show in results <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled checked /><span>Thumbnail</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled checked /><span>Title</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Category / Taxonomy badges</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Date</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Author</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Post type label</span></label>
                                <p class="is-field-help">Pick exactly what data appears next to each search hit.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Theme preset <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <div class="is-radio-row">
                                    <label class="is-radio-pill is-active"><input type="radio" disabled checked /> Light</label>
                                    <label class="is-radio-pill"><input type="radio" disabled /> Dark</label>
                                    <label class="is-radio-pill"><input type="radio" disabled /> Auto</label>
                                </div>
                                <p class="is-field-help">Light or Dark search UI. Auto matches each visitor's operating-system setting.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Brand colors <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <div class="is-color-row">
                                    <label>Primary <input type="color" value="#0077ff" disabled /></label>
                                    <label>Accent <input type="color" value="#22c55e" disabled /></label>
                                    <label>Text <input type="color" value="#1f2937" disabled /></label>
                                </div>
                                <p class="is-field-help">Tint the search UI to match your brand. Primary = buttons & links, Accent = prices & highlights, Text = result titles.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Open animation <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <select class="is-input" disabled>
                                    <option>Fade</option>
                                    <option>Slide down</option>
                                    <option>Zoom</option>
                                    <option>None</option>
                                </select>
                                <p class="is-field-help">Add a smooth motion when results appear.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Custom CSS <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <textarea class="is-input is-textarea" rows="4" disabled placeholder="/* Add custom CSS to override the default styles */"></textarea>
                                <p class="is-field-help">For pixel-perfect customization beyond the visual controls.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-woocommerce" class="is-tab-panel" data-panel="woocommerce">
                    <header class="is-panel-header">
                        <h2>WooCommerce</h2>
                        <p>Turn the search box into a sales engine and keep customers inside results until they buy.</p>
                    </header>
                    <?php if ( ! $is_woo_active ) : ?>
                        <div class="is-notice is-notice-info">
                            <strong>WooCommerce is not active.</strong> Install and activate WooCommerce to unlock these settings.
                        </div>
                    <?php endif; ?>
                    <div class="is-card">
                        <h3 class="is-subsection">Product information shown in results</h3>
                        <div class="is-field">
                            <label class="is-field-label">Search by SKU <span class="is-free-pill">FREE</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox">
                                    <input type="checkbox" name="search_by_sku" value="1" <?php checked( $search_by_sku, '1' ); ?> <?php echo $is_woo_active ? '' : 'disabled'; ?> />
                                    <span>Match products by their SKU</span>
                                </label>
                                <p class="is-field-help"><?php echo $is_woo_active ? 'Include SKU in product search when WooCommerce is active.' : 'Requires WooCommerce to be active.'; ?></p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Show price <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Display product price next to each result</span></label>
                                <p class="is-field-help">Customers see the price before they click: fewer dead clicks, more conversions.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Show sale badge <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Highlight on-sale products with a "Sale" badge</span></label>
                                <p class="is-field-help">Discounts catch the eye and drive impulse buys.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Show stock status <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Display "In stock" / "Out of stock" labels</span></label>
                                <p class="is-field-help">A clear availability badge.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Show rating stars <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Display average review rating in results</span></label>
                                <p class="is-field-help">Social proof inside the search dropdown.</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Quick actions in search results</h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Add to Cart button <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show "Add to Cart" directly in the search dropdown</span></label>
                                <p class="is-field-help">Visitors add products to cart without ever leaving the search results.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Buy Now (instant checkout) <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show "Buy Now" button. Skips cart, jumps straight to checkout</span></label>
                                <p class="is-field-help">The fastest path from search results to purchase, through this button.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Quick View modal <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Open a product preview without leaving the search results</span></label>
                                <p class="is-field-help">Image gallery, description, variations and "Add to cart", all in a popup.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Wishlist button <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Save to a browser-based wishlist (no account or 3rd party plugin)</span></label>
                                <p class="is-field-help">Capture intent even when the visitor isn't ready to buy yet. Saved in the visitor's own browser, no 3rd party dependency.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Compare button <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Compare up to 4 products in a popup table, no extra pages</span></label>
                                <p class="is-field-help">Helps customers decide between similar products faster, without ever leaving the search results.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Variation selector <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Pick variations (size, color, etc.) right inside search results</span></label>
                                <p class="is-field-help">No detail-page bounce, choose and add to cart in one move.</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Catalog visibility rules</h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Hide out-of-stock <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Don't show out-of-stock products in search results</span></label>
                                <p class="is-field-help">Stop frustrating customers with unavailable items. Uses the simple stock status.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Hide variable products <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Hide variable (parent) products from results</span></label>
                                <p class="is-field-help">Cleaner listings without indexing individual variations (safe for large stores with frequent imports).</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Logged-in only <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show search box only to logged-in customers</span></label>
                                <p class="is-field-help">Useful for B2B catalogs and members-only stores.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Frequently bought together <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show related products inside "Quick View"</span></label>
                                <p class="is-field-help">Cross-sell from the search experience to lift average order value. (Requires "Quick View" modal enabled.)</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Recently viewed <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show the customer's last viewed products when the search box is empty</span></label>
                                <p class="is-field-help">Re-engages returning visitors instantly when they open search. Tracked in the visitor's own browser cookie.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-search-sources" class="is-tab-panel" data-panel="search-sources">
                    <header class="is-panel-header">
                        <h2>Search Sources</h2>
                        <p>Control which content the engine searches and how results are weighted.</p>
                    </header>
                    <div class="is-card">
                        <h3 class="is-subsection">Searched fields</h3>
                        <div class="is-field">
                            <label class="is-field-label">Standard fields <span class="is-free-pill">FREE</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox">
                                    <input type="checkbox" name="search_fields[]" value="title" <?php checked( in_array( 'title', (array) $search_fields, true ) ); ?> />
                                    <span>Title</span>
                                </label>
                                <label class="is-checkbox">
                                    <input type="checkbox" name="search_fields[]" value="content" <?php checked( in_array( 'content', (array) $search_fields, true ) ); ?> />
                                    <span>Content</span>
                                </label>
                                <label class="is-checkbox">
                                    <input type="checkbox" name="search_fields[]" value="excerpt" <?php checked( in_array( 'excerpt', (array) $search_fields, true ) ); ?> />
                                    <span>Excerpt</span>
                                </label>
                                <p class="is-field-help">Choose which fields to include when matching search queries.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Taxonomies <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Categories</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Tags</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Custom taxonomies (auto-detected)</span></label>
                                <p class="is-field-help">Match results by category, tag, or any custom taxonomy term.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Product attributes <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Color, size, brand, material…</span></label>
                                <p class="is-field-help">"Red shoes size 42" actually finds the right product. Searches your global product attributes (requires WooCommerce).</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Custom fields / ACF <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <input type="text" class="is-input" placeholder="field_one, field_two, …" disabled />
                                <p class="is-field-help">Comma-separated list of custom field keys to include in search.</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Boost &amp; pin <span class="is-pro-pill">PRO</span></h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Pinned results</label>
                            <div class="is-field-control">
                                <textarea class="is-input is-textarea" rows="4" disabled placeholder="keyword: post_id, post_id&#10;example: shoes: 142, 388"></textarea>
                                <p class="is-field-help">Pin specific posts/products to the top for a given search keyword. One rule per line, e.g. shoes: 142, 388 pins those IDs when someone searches "shoes".</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Boost rules</label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Boost newer content</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Boost best-sellers</span></label>
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Boost featured products</span></label>
                                <p class="is-field-help">Reorder results based on what's most likely to convert. Priority: featured → best-sellers → newer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Exclude &amp; refine <span class="is-pro-pill">PRO</span></h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Exclude by taxonomy</label>
                            <div class="is-field-control">
                                <input type="text" class="is-input" placeholder="category-slug, tag-slug, …" disabled />
                                <p class="is-field-help">Hide all posts in a chosen category, tag or term.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Synonyms</label>
                            <div class="is-field-control">
                                <textarea class="is-input is-textarea" rows="3" disabled placeholder="laptop = notebook, computer&#10;tv = television"></textarea>
                                <p class="is-field-help">Map customer terms to your real product names.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Stop words</label>
                            <div class="is-field-control">
                                <input type="text" class="is-input" placeholder="and, the, of, with, …" disabled />
                                <p class="is-field-help">Words to ignore when matching the query.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Fuzzy / typo tolerance</label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Match close misspellings (Levenshtein)</span></label>
                                <p class="is-field-help">"shooes" still finds your shoes.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-analytics" class="is-tab-panel" data-panel="analytics">
                    <header class="is-panel-header">
                        <h2>Analytics</h2>
                        <p>See exactly what your visitors are searching for.</p>
                    </header>
                    <div class="is-card">
                        <div class="is-stats-row">
                            <div class="is-stat">
                                <span class="is-stat-label">Tracked queries</span>
                                <span class="is-stat-value"><?php echo esc_html( count( (array) $top_searches ) ); ?></span>
                            </div>
                            <div class="is-stat">
                                <span class="is-stat-label">Top term</span>
                                <span class="is-stat-value"><?php echo esc_html( ! empty( $top_searches ) ? $top_searches[0]->query : '—' ); ?></span>
                            </div>
                            <div class="is-stat is-pro-locked">
                                <span class="is-stat-label">Click-through rate <span class="is-pro-pill">PRO</span></span>
                                <span class="is-stat-value">—</span>
                            </div>
                            <div class="is-stat is-pro-locked">
                                <span class="is-stat-label">Cart conversions <span class="is-pro-pill">PRO</span></span>
                                <span class="is-stat-value">—</span>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Top 20 searches <span class="is-free-pill">FREE</span></h3>
                        <?php if ( ! empty( $top_searches ) ) : ?>
                            <ol class="is-search-list">
                                <?php foreach ( $top_searches as $row ) : ?>
                                    <li>
                                        <span class="is-search-term"><?php echo esc_html( $row->query ); ?></span>
                                        <span class="is-search-count"><?php echo esc_html( $row->count ); ?> times</span>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else : ?>
                            <p class="is-empty-state">No searches tracked yet. Start your search box and come back here.</p>
                        <?php endif; ?>
                        <div class="is-row-end">
                            <button type="button" class="is-btn is-btn-ghost" id="instant-search-flush">Clear analytics</button>
                        </div>
                    </div>
                    <div class="is-card is-pro-locked">
                        <h3 class="is-subsection">Zero-results queries <span class="is-pro-pill">PRO</span></h3>
                        <p class="is-field-help">What visitors searched for and didn't find: the highest-value list in any shop. Add these products to your stock (or add synonyms in "Search Sources") and watch revenue jump.</p>
                        <div class="is-fake-chart"></div>
                    </div>
                    <div class="is-card is-pro-locked">
                        <h3 class="is-subsection">Searches per day <span class="is-pro-pill">PRO</span></h3>
                        <p class="is-field-help">Daily search volume over the last 30 days, with the click-through rate overlaid as a red line.</p>
                        <div class="is-fake-chart"></div>
                    </div>
                    <div class="is-card is-pro-locked">
                        <h3 class="is-subsection">Reports &amp; export <span class="is-pro-pill">PRO</span></h3>
                        <div class="is-field">
                            <label class="is-field-label">Weekly email report</label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Send a top-searches summary every Monday</span></label>
                                <p class="is-field-help">Stay on top of buyer intent without ever opening this dashboard. Press “Save Changes” after toggling this on or off.</p>
                            </div>
                        </div>
                        <div class="is-field">
                            <label class="is-field-label">Export to CSV</label>
                            <div class="is-field-control">
                                <button type="button" class="is-btn is-btn-ghost" disabled>Download CSV</button>
                                <p class="is-field-help">Export your search terms: searches, clicks, CTR, cart adds and zero-results.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-advanced" class="is-tab-panel" data-panel="advanced">
                    <header class="is-panel-header">
                        <h2>Advanced</h2>
                        <p>Power-user features and behavior controls.</p>
                    </header>
                    <div class="is-card">
                        <h3 class="is-subsection">User experience</h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Floating search button <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Show a circular search button bottom-right on every page</span></label>
                                <p class="is-field-help">Search is one tap away anywhere on the site, even without a header bar. It opens your overlay search popup.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Keyboard shortcut <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <select class="is-input" disabled>
                                    <option>Cmd / Ctrl + K</option>
                                    <option>/ (slash key)</option>
                                    <option>Disabled</option>
                                </select>
                                <p class="is-field-help">Open search instantly with a hotkey. Be sure they know about this shortcut, if enabled.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Auto-focus on page load <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Cursor jumps into the search box automatically</span></label>
                                <p class="is-field-help">Great for landing pages dedicated to search. In "Overlay" search mode this opens the search popup on load.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Search history <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Remember the visitor's recent searches</span></label>
                                <p class="is-field-help">Shows the visitor's last queries only when they open an empty search box, it disappears the moment they start typing, so it never interrupts a search. Stored in the visitor's own browser (no account).</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Zero-results rescue <span class="is-pro-pill">PRO</span></h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">"Did you mean?" suggestions</label>
                            <div class="is-field-control">
                                <label class="is-checkbox"><input type="checkbox" disabled /><span>Suggest popular searches when the query has no results</span></label>
                                <p class="is-field-help">Catches typos and saves the click. Suggests real past searches closest to what was typed.</p>
                            </div>
                        </div>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Fallback content</label>
                            <div class="is-field-control">
                                <select class="is-input" disabled>
                                    <option>Show best-sellers</option>
                                    <option>Show featured products</option>
                                    <option>Show on-sale products</option>
                                    <option>Custom message</option>
                                </select>
                                <p class="is-field-help">Never let a search end with "no results found".</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Voice search</h3>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label">Voice language <span class="is-pro-pill">PRO</span></label>
                            <div class="is-field-control">
                                <select class="is-input" disabled>
                                    <option>Auto-detect (browser)</option>
                                    <option>English (US)</option>
                                    <option>English (UK)</option>
                                    <option>Spanish</option>
                                    <option>French</option>
                                    <option>German</option>
                                    <option>Romanian</option>
                                    <option>… 30+ languages</option>
                                </select>
                                <p class="is-field-help">Lock voice recognition to a specific language for accuracy. (Requires "Voice Search" enabled in the "General" tab.)</p>
                            </div>
                        </div>
                    </div>
                    <div class="is-card">
                        <h3 class="is-subsection">Shortcode</h3>                     

                        <div class="is-field">
                            <label class="is-field-label">Shortcode <span class="is-free-pill">FREE</span></label>
                            <div class="is-field-control">
                                <code class="is-shortcode">[instant_search]</code>
                                <p class="is-field-help">Drop this anywhere: a post, page, widget, or a theme template.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="tab-license" class="is-tab-panel" data-panel="license">
                    <header class="is-panel-header">
                        <h2>License</h2>
                        <p>Activate your PRO license to unlock all advanced features.</p>
                    </header>
                    <div class="is-card">
                        <?php if ( $is_pro_active ) : ?>
                            <div class="is-license-active">
                                <span class="is-license-dot is-license-dot-active"></span>
                                <strong>License Active</strong>
                                <p>Your PRO license is currently active. All features are unlocked.</p>
                            </div>
                        <?php else : ?>
                            <div class="is-license-inactive">
                                <span class="is-license-dot is-license-dot-inactive"></span>
                                <strong>You're on the FREE version</strong>
                                <p>Unlock the full WooCommerce sales engine, advanced analytics, search boosting, and more.</p>
                                <ul class="is-feature-bullets">
                                    <li>✓ Add to Cart &amp; Buy Now from search results</li>
                                    <li>✓ Quick View product modals</li>
                                    <li>✓ Hide out-of-stock, show price &amp; ratings</li>
                                    <li>✓ Boost &amp; pin priority products</li>
                                    <li>✓ Detailed analytics &amp; weekly email reports</li>
                                    <li>✓ Floating search button &amp; keyboard shortcuts</li>
                                </ul>
                                <a href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noopener" class="is-btn is-btn-upgrade">Get Pro version</a>
                            </div>
                        <?php endif; ?>
                        <div class="is-field is-pro-locked">
                            <label class="is-field-label" for="license_key_demo">License key</label>
                            <div class="is-field-control">
                                <input type="text" id="license_key_demo" class="is-input" placeholder="Paste your license key here…" disabled />
                                <p class="is-field-help">Available after upgrading to PRO.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="is-form-actions">
                    <button type="submit" name="save_changes" value="1" class="is-btn is-btn-primary">Save Changes</button>
                </div>
            </form>
        </main>
        <aside class="is-app-sidebar">
            <div class="is-side-card">
                <h3 class="is-side-title">Status</h3>
                <?php if ( $is_pro_active ) : ?>
                    <div class="is-status-pill is-status-active">License Active</div>
                    <p class="is-side-text">Your PRO license is active and all features are unlocked.</p>
                <?php else : ?>
                    <div class="is-status-pill is-status-free">FREE Version</div>
                    <p class="is-side-text">You're using the free version of Instant Search. Upgrade to PRO to unlock the full sales engine for your shop.</p>
                    <a href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noopener" class="is-btn is-btn-upgrade is-btn-block">Get Pro version</a>
                <?php endif; ?>
                <ul class="is-side-links">
                    <li><a href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener">Documentation</a></li>
                    <li><a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener">Support</a></li>
                    <li><a href="https://wordpress.org/support/plugin/instant-search/reviews/#new-post" target="_blank" rel="noopener">Leave a review ★</a></li>
                </ul>
            </div>
            <div class="is-side-card is-side-preview<?php echo $is_pro_active ? '' : ' is-side-preview-locked'; ?>">
                <h3 class="is-side-title">Live Preview <span class="is-pro-pill">PRO</span></h3>
                <?php if ( $is_pro_active ) : ?>
                    <div class="is-preview-window is-preview-method-<?php echo esc_attr( $search_method ); ?>">
                        <div class="is-preview-search">
                            <span class="is-preview-search-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="14" height="14"><path fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" d="M21 21l-4.3-4.3M10 17a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/></svg>
                            </span>
                            <input type="text" id="is-preview-input" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" />
                            <span class="is-preview-clear">×</span>
                        </div>

                        <div class="is-preview-results is-preview-<?php echo esc_attr( $display_style ); ?>" id="is-preview-results">
                            <a class="is-preview-result">
                                <div class="is-preview-thumb is-preview-thumb-1"></div>
                                <div class="is-preview-text">
                                    <span class="is-preview-title">Basic Dress</span>
                                    <span class="is-preview-meta">Post</span>
                                </div>
                            </a>
                            <a class="is-preview-result">
                                <div class="is-preview-thumb is-preview-thumb-2"></div>
                                <div class="is-preview-text">
                                    <span class="is-preview-title">Accessories Guide</span>
                                    <span class="is-preview-meta">Page</span>
                                </div>
                            </a>
                            <a class="is-preview-result">
                                <div class="is-preview-thumb is-preview-thumb-3"></div>
                                <div class="is-preview-text">
                                    <span class="is-preview-title">10 Summer Fashion Tips</span>
                                    <span class="is-preview-meta">Post</span>
                                </div>
                            </a>
                            <a class="is-preview-result">
                                <div class="is-preview-thumb is-preview-thumb-4"></div>
                                <div class="is-preview-text">
                                    <span class="is-preview-title">Gray Linen Shirt</span>
                                    <span class="is-preview-meta">Product</span>
                                </div>
                            </a>
                            <a class="is-preview-result is-preview-product">
                                <div class="is-preview-thumb is-preview-thumb-5"></div>
                                <div class="is-preview-text">
                                    <span class="is-preview-title">Modular Sofa Light Gray</span>
                                    <span class="is-preview-price">$2,199</span>
                                </div>
                                <button type="button" class="is-preview-atc" disabled>ADD TO CART</button>
                            </a>
                        </div>
                    </div>
                    <p class="is-side-text-small">Preview is illustrative — your real search inherits your theme's styling.</p>
                <?php else : ?>
                    <div class="is-preview-locked-wrap">
                        <div class="is-preview-locked-blur" aria-hidden="true">
                            <div class="is-preview-window">
                                <div class="is-preview-search">
                                    <span class="is-preview-search-icon">
                                        <svg viewBox="0 0 24 24" width="14" height="14"><path fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" d="M21 21l-4.3-4.3M10 17a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/></svg>
                                    </span>
                                    <span class="is-preview-fake-input"></span>
                                </div>
                                <div class="is-preview-results">
                                    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                                        <div class="is-preview-result">
                                            <div class="is-preview-thumb is-preview-thumb-<?php echo $i; ?>"></div>
                                            <div class="is-preview-text">
                                                <span class="is-preview-title">&#9608;&#9608;&#9608;&#9608;&#9608;&#9608;&#9608;</span>
                                                <span class="is-preview-meta">&#9608;&#9608;&#9608;&#9608;</span>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="is-preview-locked-cta">
                            <span class="is-preview-locked-icon" aria-hidden="true">⚡</span>
                            <p>See a live preview of your search results as you configure settings.</p>
                            <a href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noopener" class="is-btn is-btn-upgrade">Get Pro version</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
    <form id="instant-search-flush-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;">
        <input type="hidden" name="action" value="flush_search_queries_action" />
        <?php wp_nonce_field( 'instant_search_flush', 'instant_search_flush_nonce' ); ?>
        <input type="hidden" name="flush_search_queries" value="1" />
    </form>
</div>