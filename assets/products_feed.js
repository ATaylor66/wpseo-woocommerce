jQuery(document).ready(function ($) {

	$('.wpseo_woo_pf_categories').change(function (event) {

		// Get and format the parents
		var parents = [];
		var parent_options = $('.wpseo_woo_pf_categories').find('option:selected');
		$.each(parent_options, function(k,option) {
			parents[ parents.length ] = $(option).text();
		})

		// Do the AJAX call
		var data = {
			action         : 'wpseo_woo_get_products_feed_categories',
			wpseo_woo_nonce: wpseo_woo_products_feed_nonce,
			parents        : parents
		};

		$.post(ajaxurl, data, function (response) {
			//alert("Response: " + response);
			console.info(response);
		});

	});


});