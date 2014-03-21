WooCommerce Yoast SEO
=====================
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.1.2
Depends: wordpress-seo

This extension to WooCommerce and WordPress SEO by Yoast makes sure there's perfect communication between the two plugins.

Description
-----------

This extension to WooCommerce and WordPress SEO by Yoast makes sure there's perfect communication between the two plugins.

Installation
------------

1. Go to Plugins -> Add New.
2. Click "Upload" right underneath "Install Plugins".
3. Upload the zip file that this readme was contained in.
4. Activate the plugin.
5. Go to SEO -> Licenses and enter your WooCommerce SEO license key.
6. Save settings, your license key will be validated. If all is well, you should now see the WooCommerce SEO settings.

Frequently Asked Questions
--------------------------

You can find the FAQ [online here](https://yoast.com/wordpress/plugins/yoast-woocommerce-seo/faq/).

Changelog
=========

1.1.2
---

* Fixed a bug where the breadcrumb caused a fatal error.

1.1.1
---

* Added Yoast license manager to plugin.

1.1
---

Compatibility update for WP SEO v1.5 including application of a number of best practices.

* Bugfixes:
	* Fixed: shortcodes should be removed from ogdesc.
	* Fixed: duplicate twitter domain meta tag
	* Fixed: error loading stylesheet (WPSEO_URL no longer defined).

* Additional enhancements
	* Change the minimum content length requirements to 200, instead of the WP SEO default of 300.
	* Add a length test for the products short description.
	* Make sure the content analysis tests use the product images as well.
	* If a product category has a description, use it for the OpenGraph description.
	* Switch to general WP SEO Licensing class

1.0.1
-----

* Add check whether WordPress and WordPress SEO by Yoast are installed and up-to-date

1.0
---

* Initial version.


== Upgrade Notice ==

1.1
---
* Please upgrade the WordPress SEO plugin to version 1.5 as well for compatibility.