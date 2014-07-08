<?php

class WPSEO_Woo_Products_Feed_Manager {

	/**
	 *  The JavaScript for categories on product edit pages
	 */
	public function product_edit_js_nonce() {
		global $post, $pagenow;

		// Only use script on product page
		if ( $pagenow != 'post.php' || null == $post || 'product' != $post->post_type ) {
			return;
		}

		//Set Your Nonce
		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'var wpseo_woo_products_feed_nonce = "' . $ajax_nonce = wp_create_nonce( 'wpseo-woo-very-secret' ) . '";' . PHP_EOL;
		echo '</script>' . PHP_EOL;
	}

	/**
	 * Enqueue the products feed JavaScript file
	 *
	 * @param $hook
	 */
	public function enqueue_products_feed_js( $hook ) {
		global $post;

		// Only use script on product page
		if ( $hook != 'post.php' || null == $post || 'product' != $post->post_type ) {
			return;
		}

		wp_enqueue_script( 'wpseo_woo_products_feed', plugin_dir_url( Yoast_WooCommerce_SEO::get_plugin_file() ) . '/assets/products_feed.js' );
	}

	/**
	 * AJAX get categories method
	 */
	public function ajax_get_products_feed_categories() {

		// Check the AJAX nonce
		check_ajax_referer( 'wpseo-woo-very-secret', 'wpseo_woo_nonce', '' );

		// Get the parent
		$parents = ( ( isset( $_POST['parents'] ) ) ? $_POST['parents'] : null );

		// Load the taxonomies
		$categories = $this->get_categories( $parents );

		// Testing
		echo json_encode( $categories );

		exit;

	}

	/**
	 * Return the top level products feed categories
	 *
	 * @param array $parents
	 *
	 * @return array
	 */
	public function get_categories( $parents = null ) {

		// Load the taxonomies
		$taxonomies = require( dirname( Yoast_WooCommerce_SEO::get_plugin_file() ) . '/assets/taxonomies.php' );

		// The target tax
		$target_tax = $taxonomies;

		if ( null != $parents && is_array( $parents ) && count( $parents ) > 0 ) {
			foreach ( $parents as $parent ) {
				$target_tax = $target_tax[ $parent ];
			}
		}

		// Get the correct categories
		$categories = array_keys( $target_tax );

		// Sort categories
		sort( $categories );

		// Prepend 'none' to categories
		array_unshift( $categories, __( 'None', 'yoast-woo-seo' ) );

		// Return categories
		return $categories;
	}

}