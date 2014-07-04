<?php

class WPSEO_Woo_Products_Feed {

	private $prefix = 'g:';

	private $items;

	public function __construct() {

		$this->prepare_data();

	}

	/**
	 * Prepare the data for the products feed
	 */
	private function prepare_data() {

		// Setup an empty array
		$this->items = array();

		// Get the WooCommerce product IDs
		$post_ids = get_posts( array( 'fields' => 'ids', 'post_type' => 'product' ) );
		if ( count( $post_ids ) > 0 ) {
			foreach ( $post_ids as $post_id ) {

				// Get the WooCommerce product
				$wc_product = get_product( $post_id );

//				var_dump($wc_product);

				// Create a new feed item
				$item = array(
					'title'                                   => $wc_product->get_title(),
					'link'                                    => $wc_product->get_permalink(),
					'description'                             => $wc_product->post->post_content,
					$this->prefix . 'id'                      => (int) $wc_product->is_type( 'variation' ) ? $wc_product->get_variation_id() : $wc_product->id,
					$this->prefix . 'google_product_category' => 'test'
				);

				// Add the new item to our items array
				$this->items[] = $item;

			}
		}


	}

	public function generate_rss() {

		echo '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
		echo '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
		echo '<channel>' . PHP_EOL;


		echo '<title>' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;
		echo '<link>' . get_bloginfo( 'url' ) . '</link>' . PHP_EOL;
		echo '<description>' . get_bloginfo( 'description' ) . '</description>' . PHP_EOL;

		if ( is_array( $this->items ) && count( $this->items ) > 0 ) {
			foreach ( $this->items as $item ) {
				echo '<item>' . PHP_EOL;

				foreach ( $item as $tag => $value ) {
					echo '<' . $tag . '>' . $value . '</' . $tag . '>' . PHP_EOL;
				}

				echo '</item>' . PHP_EOL;
			}
		}


		echo '</channel>' . PHP_EOL;
		echo '</rss>' . PHP_EOL;

	}

}