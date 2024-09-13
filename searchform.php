<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; 
    }
?>
	<div class="middleform">
	<form role="search" style="display:inline-block;" method="get" id="searchform2" action="<?php echo esc_url(home_url('/')); ?>">
		<div class="search-wrapper2" >
			<input type="text" value="" id="s2" placeholder="<?php echo esc_attr(get_option('instant_search_placeholder', 'What are we searching for today?')); ?>" />
			<?php if (get_option('instant_search_method', 'overlay') === 'inline' && get_option('instant_search_enable_voice_search', '1') === '1') : ?> 
			<button type="button" id="voice-search-btn2" aria-label="Voice Search"><i class="fas fa-microphone"></i></button> 		
			 <?php endif; ?>		 
		</div>
	</form>
	</div>
	<div id="myModal" class="modal" style="z-index:999;">
		<div class="modal-content">
			<div id="search-results" class="<?php echo esc_attr(get_option('instant_search_display_style', 'list')); ?> <?php echo esc_attr(get_option('instant_search_method', 'overlay')); ?>"></div>
			<span class="close">&times;</span>
			<div class="middleform">
			<form role="search" style="display:inline-block;" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
				<div class="search-wrapper">
					<input type="text" value="" name="s" id="s" placeholder="<?php echo esc_attr(get_option('instant_search_placeholder', 'What are we searching for today?')); ?>" />
			 <?php if (get_option('instant_search_enable_voice_search', '1') === '1') : ?> 	
			<button type="button" id="voice-search-btn" aria-label="Voice Search"><i class="fas fa-microphone"></i></button>			
			 <?php endif; ?>			
			</div>
				<input type="hidden" name="post_type[]" value="<?php echo esc_attr(implode(',', get_option('instant_search_post_types', array('post', 'page')))); ?>">
			</form>
			</div>
		</div>
	</div>
	<script>
	jQuery(document).ready(function($) {
		var modal = document.getElementById("myModal");
		var btn = document.getElementById("s2");
	var span = document.getElementsByClassName("close")[0];
	btn.onclick = function() {
		if (instant_search.search_method === 'overlay') {
			modal.style.display = "block"; 
		} else {
			$('#s2').trigger('input'); 
		}
	}
			span.onclick = function() {
				modal.style.display = "none"; 
			}
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none"; 
				}
			}
	});
	</script>