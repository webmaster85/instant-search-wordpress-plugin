<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
$placeholder = get_option( 'instant_search_placeholder', 'What are we searching for today?' );
$display_style = get_option( 'instant_search_display_style', 'list' );
$search_method = get_option( 'instant_search_method', 'overlay' );
$voice_enabled = ( '1' === get_option( 'instant_search_enable_voice_search', '1' ) );
$post_types_opt = get_option( 'instant_search_post_types', array( 'post', 'page' ) );
?>
<div class="middleform">
    <form role="search" style="display:inline-block;" method="get" id="searchform2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <div class="search-wrapper2">
            <input type="text" value="" id="s2" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
            <?php if ( 'inline' === $search_method && $voice_enabled ) : ?>
                <button type="button" id="voice-search-btn2" class="instant-voice-btn" aria-label="<?php echo esc_attr__( 'Voice Search', 'instant-search' ); ?>">
                    <?php if ( wp_style_is( 'instant_search-fa', 'enqueued' ) ) : ?>
                        <i class="fas fa-microphone" aria-hidden="true"></i>
                    <?php else : ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 14a3 3 0 0 0 3-3V7a3 3 0 1 0-6 0v4a3 3 0 0 0 3 3zM5 11a7 7 0 0 0 14 0M12 18v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    <?php endif; ?>
                </button>
            <?php endif; ?>
            <button type="submit" class="instant-search-submit" aria-label="<?php echo esc_attr__( 'Search', 'instant-search' ); ?>">
                <?php if ( wp_style_is( 'instant_search-fa', 'enqueued' ) ) : ?>
                    <i class="fas fa-search" aria-hidden="true"></i>
                <?php else : ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M21 21l-4.3-4.3M10 17a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                <?php endif; ?>
            </button>
        </div>
    </form>
</div>
<div id="myModal" class="modal" style="z-index:999;">
    <div class="modal-content">      
        <span class="close" aria-label="<?php echo esc_attr__( 'Close', 'instant-search' ); ?>">&times;</span>
        <div class="middleform">
            <form role="search" style="display:inline-block;" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="search-wrapper">
                    <input type="text" value="" name="s" id="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
                    <?php if ( $voice_enabled ) : ?>
                        <button type="button" id="voice-search-btn" class="instant-voice-btn" aria-label="<?php echo esc_attr__( 'Voice Search', 'instant-search' ); ?>">
                            <?php if ( wp_style_is( 'instant_search-fa', 'enqueued' ) ) : ?>
                                <i class="fas fa-microphone" aria-hidden="true"></i>
                            <?php else : ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M12 14a3 3 0 0 0 3-3V7a3 3 0 1 0-6 0v4a3 3 0 0 0 3 3zM5 11a7 7 0 0 0 14 0M12 18v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                    <button type="submit" class="instant-search-submit" aria-label="<?php echo esc_attr__( 'Search', 'instant-search' ); ?>">
                        <?php if ( wp_style_is( 'instant_search-fa', 'enqueued' ) ) : ?>
                            <i class="fas fa-search" aria-hidden="true"></i>
                        <?php else : ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M21 21l-4.3-4.3M10 17a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        <?php endif; ?>
                    </button>
                </div>
                <input type="hidden" name="post_type[]" value="<?php echo esc_attr( implode( ',', (array) $post_types_opt ) ); ?>">
            </form>
            <div id="search-results" class="<?php echo esc_attr( $display_style ); ?> <?php echo esc_attr( $search_method ); ?>"></div>
        </div>
    </div>
</div>
<script>
jQuery(function($) {
    var modal = document.getElementById('myModal');
    var btn   = document.getElementById('s2');
    var span  = document.getElementsByClassName('close')[0];
    if (btn) {
        btn.onclick = function() {
            if (window.instant_search && instant_search.search_method === 'overlay') {
                modal.style.display = 'block';
            } else {
                $('#s2').trigger('input'); 
            }
        };
    }
    if (span) {
        span.onclick = function() { modal.style.display = 'none'; };
    }
    window.onclick = function(event) {
        if (event.target === modal) { modal.style.display = 'none'; }
    };
});
</script>