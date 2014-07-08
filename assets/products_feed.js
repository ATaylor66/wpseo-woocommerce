jQuery(document).ready(function ($) {

	Array.max = function (array) {
		return Math.max.apply(Math, array);
	};

	$.fn.wpseo_bind_pf_category = function () {

		// The current select input
		var cur_select = this;

		// Find highest rel
		var rels = new Array();
		$('.wpseo_woo_pf_categories').each(function (index) {
			if (undefined != $(this).attr('rel')) {
				rels.push($(this).attr('rel'));
			}

		});

		// Set the rel number
		max_rel = -1;
		if (rels.length > 0) {
			var max_rel = Array.max(rels);
			if (isNaN(max_rel)) {
				max_rel = -1;
			}
		}

		console.info(max_rel);
		$(cur_select).attr('rel', (max_rel + 1 ));

		// Bind change event
		$(cur_select).change(function (event) {

			// Remove child category select boxes
			var cur_rel = $(cur_select).attr('rel');
			$.each($('.wpseo_woo_pf_categories'), function (k, select) {
				if ($(select).attr('rel') > cur_rel) {
					$(select).remove();
				}
			});

			// Get and format the parents
			var parents = [];
			var parent_options = $('.wpseo_woo_pf_categories').find('option:selected');
			$.each(parent_options, function (k, option) {
				parents[ parents.length ] = $(option).text();
			});

			// Do the AJAX call
			var data = {
				action         : 'wpseo_woo_get_products_feed_categories',
				wpseo_woo_nonce: wpseo_woo_products_feed_nonce,
				parents        : parents
			};

			$.post(ajaxurl, data, function (categories) {

				if ('' != categories) {
					categories = $.parseJSON(categories);

					if (categories.length > 1) {

						// Count the number of child levels
						var child_depth = $('.wpseo_woo_pf_categories').length;

						// Create the select
						var select = $('<select>').attr('name', 'yoast_wpseo_products-feed-category[' + child_depth + ']').attr('id', 'feed-category-' + child_depth).addClass('wpseo_woo_pf_categories');

						// Loop through the categories and add them to the select input
						for (var i = 0; i < categories.length; i++) {
							$(select).append($('<option>').attr('value', categories[i]).html(categories[i]));
						}

						// Add the select input to the DOM
						$('.wpseo_woo_pf_categories:first-child').parent().append(select);

						// Bind change event to select box
						$(select).wpseo_bind_pf_category();

					}

				}

			});

		});

	};

	// Bind the select inputs
	$.each($('.wpseo_woo_pf_categories'), function (k, v) {
		$(v).wpseo_bind_pf_category();
	});

});