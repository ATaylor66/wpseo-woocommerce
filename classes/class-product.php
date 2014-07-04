<?php

if( ! class_exists( 'WPSEO_Woo_Product' ) ) {

	/**
	 * Class WPSEO_Woo_Product
	 */
	class WPSEO_Woo_Product extends Yoast_Product {

		public function __construct() {
			parent::__construct(
					'https://yoast.com',
					'WooCommerce Yoast SEO',
					plugin_basename( Yoast_WooCommerce_SEO::get_plugin_file() ),
					Yoast_WooCommerce_SEO::VERSION,
					'https://yoast.com/wordpress/plugins/yoast-woocommerce-seo/',
					'admin.php?page=wpseo_licenses#top#licenses',
					'yoast-woo-seo',
					'Yoast'
			);
		}

	}

}