(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function($) {
		var searchMethod = instant_search.search_method;
		var displayStyle = instant_search.display_style;
		function getOrCreateOverlay() {
			let overlay = $('#search-overlay');
			if (overlay.length === 0) {
				overlay = $('<div id="search-overlay" class="search-overlay" style="display: none;"></div>');
				let searchResults = $('<div id="search-results" class="' + displayStyle + '" style="display: none;"></div>');
				overlay.append(searchResults);
				$('.modal-content').append(overlay);
			}
			return overlay;
		}
		var currentRequest = null;
		function getOrCreateInlineWrapper() {
			let wrapper = $('#inline-search-results');
			if (wrapper.length === 0) {
				wrapper = $('<div id="inline-search-results" class="search-results ' + displayStyle + '"></div>');
				$('#s2').closest('.search-wrapper2').append(wrapper);
			}
			return wrapper;
		}
		function performSearch(query) {       
			var overlay = getOrCreateOverlay();
			var searchResults = overlay.find('#search-results');
			var inlineWrapper = getOrCreateInlineWrapper();
			if (query === '') {	
				if (searchMethod === 'overlay') {
					overlay.fadeOut(0, function() {
						searchResults.hide();
					});
				} else {
					inlineWrapper.hide();
				}
			} else {	
				if (currentRequest != null) {
					currentRequest.abort();
				}
				currentRequest = $.ajax({
					url: instant_search.ajax_url,
					type: 'post',
					data: {
						action: 'instant_search',
						query: query
					},
					success: function(result) {					
						if (searchMethod === 'overlay') {
							searchResults.html(result).css('display', 'block');
							overlay.fadeIn(0);
						} else {
							inlineWrapper.html(result).attr('class', 'search-results ' + displayStyle).show();
						}
					},
					complete: function() {
						currentRequest = null; 
					}
				});
			}
		}
		$('#s, #s2').on('input', function() {
			var query = $('#s').val() || $('#s2').val();
			performSearch(query);
		});
		if (searchMethod === 'overlay') {
			$(document).on('click', '#search-overlay', function(e) {
				if ($(e.target).closest('#search-results').length === 0) {
					$('#search-overlay').fadeOut(function() {
						$(this).remove();
					});
				}
			});
		}
		$(document).on('click', '#search-results', function(e) {
			e.stopPropagation();
		});	
		if ('webkitSpeechRecognition' in window) {
			var recognition = new webkitSpeechRecognition();
			recognition.continuous = false;
			recognition.interimResults = false;
	
			recognition.onresult = function(event) {
				var transcript = event.results[0][0].transcript;
				var cleanedTranscript = transcript.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, "");
				$('#s, #s2').val(cleanedTranscript).trigger('input'); 
			};
	
			$('#voice-search-btn, #voice-search-btn2').on('click', function() {
				recognition.start(); 
			});
		} else {
			$('#voice-search-btn, #voice-search-btn2').hide(); 
		}
	});
	
})( jQuery );



