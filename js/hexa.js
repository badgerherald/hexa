jQuery(document).ready(function($) {

	// Remove the preflight container until its loaded.
	jQuery(".preflight-container .dfw-unit").on("dfw:beforeAdLoaded", function() {
		// add the class loaded to the preflight container.
		var preflightcontainer = $(this).closest(".preflight-container");
		preflightcontainer.hide();
	});

	// Decorate the preflight once its loaded on the page: 
	// Adds a didLoad class to the preflight container.
	jQuery(".preflight-container .dfw-unit").on("dfw:afterAdLoaded", function() {
		// add the class loaded to the preflight container.
		var preflightcontainer = $(this).closest(".preflight-container");
		preflightcontainer.addClass("loaded");
		preflightcontainer.show();
	});

	jQuery(".ad-in-content").on("dfw:afterAdLoaded", function(event,gptEvent) {
		if(!gptEvent.isEmpty) {
			var adContainer = $(this).closest(".ad-in-content");
			adContainer.css( "display", "block");
		}
	});

});