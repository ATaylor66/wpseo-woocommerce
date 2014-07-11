<?php

class WPSEO_Woo_Products_Feed {

	private $prefix = 'g:';

	private $items;

	public function __construct() {

		$this->prepare_data();

	}

	/**
	 * Format the feed price
	 *
	 * @param $price
	 *
	 * @return string
	 */
	private function format_feed_price( $price ) {
		$currency = get_woocommerce_currency();

		return number_format( $price, 2 ) . ' ' . $currency;
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @return string valid PHP timezone string
	 */
	private function wp_get_timezone_string() {

		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) ) {
			return $timezone;
		}

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
			return 'UTC';
		}

		// adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// attempt to guess the timezone string from the UTC offset
		$timezone = timezone_name_from_abbr( '', $utc_offset );

		// last try, guess timezone string manually
		if ( false === $timezone ) {

			$is_dst = date( 'I' );

			foreach ( timezone_abbreviations_list() as $abbr ) {
				foreach ( $abbr as $city ) {
					if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
						return $city['timezone_id'];
					}
				}
			}
		}

		// fallback to UTC
		return 'UTC';
	}

	/**
	 * Prepare the data for the products feed
	 */
	private function prepare_data() {

		// Setup an empty array
		$this->items = array();

		// Datetime string
		$timezone_string = $this->wp_get_timezone_string();


		// Get the WooCommerce product IDs
		$post_ids = get_posts( array( 'fields' => 'ids', 'post_type' => 'product' ) );
		if ( count( $post_ids ) > 0 ) {
			foreach ( $post_ids as $post_id ) {

				// Get the WooCommerce product
				$wc_product = get_product( $post_id );

//				var_dump( $wc_product );

				// Get the category meta
				$category        = '';
				$categories_meta = get_post_meta( $post_id, 'yoast_wpseo_products-feed-category', true );
				if ( is_array( $categories_meta ) && count( $categories_meta ) > 0 ) {
					$category = htmlentities( implode( " > ", $categories_meta ) );
				}

				// Get the condition post meta
				$condition = WPSEO_Meta::get_value( 'pf-condition', $post_id );
				if ( '' == $condition ) {
					$condition = 'new';
				}

				// Create a new feed item
				$item = array(
					'title'                                   => $wc_product->get_title(),
					'link'                                    => $wc_product->get_permalink(),
					'description'                             => $wc_product->post->post_content,
					$this->prefix . 'id'                      => (int) $wc_product->is_type( 'variation' ) ? $wc_product->get_variation_id() : $wc_product->id,
					$this->prefix . 'google_product_category' => $category,
					$this->prefix . 'product_type'            => $category,
					$this->prefix . 'image_link'              => wp_get_attachment_url( get_post_thumbnail_id( $wc_product->id ) ),
					$this->prefix . 'condition'               => $condition,
					$this->prefix . 'availability'            => ( $wc_product->is_in_stock() ) ? 'in stock' : 'out of stock',
				);

				// Add a sale price if the product is on sale
				if ( $wc_product->is_on_sale() && '' != $wc_product->get_sale_price() ) {

					// Add the regular price to the feed item
					$item[ $this->prefix . 'price' ] = $this->format_feed_price( $wc_product->get_regular_price() );

					// Add the sale price to the feed item
					$item[ $this->prefix . 'sale_price' ] = $this->format_feed_price( $wc_product->get_sale_price() );

					// Check if there's a date range for the sale
					$sale_price_dates_from = ( $date = get_post_meta( $wc_product->id, '_sale_price_dates_from', true ) ) ? new DateTime( date_i18n( 'Y-m-d H:i:s', $date ), new DateTimeZone( $timezone_string ) ) : '';
					$sale_price_dates_to   = ( $date = get_post_meta( $wc_product->id, '_sale_price_dates_to', true ) ) ? new DateTime( date_i18n( 'Y-m-d H:i:s', $date ), new DateTimeZone( $timezone_string ) ) : '';

					if ( '' != $sale_price_dates_from && '' != $sale_price_dates_to ) {
						$item[ $this->prefix . 'sale_price_effective_date' ] = $sale_price_dates_from->format( 'c' ) . '/' . $sale_price_dates_to->format( 'c' );
					}

				} else {
					// There is no sale so add the normal price to the feed item
					$item[ $this->prefix . 'price' ] = $this->format_feed_price( $wc_product->get_price() );
				}

				// Unique Product Identifiers
				$identifier_exists = WPSEO_Meta::get_value( 'pf-identifier_exists', $wc_product->id );

				// Check if 'identifier_exists' is 'true'
				if ( 'true' == $identifier_exists ) {

					$brand = WPSEO_Meta::get_value( 'pf-brand', $wc_product->id );
					if ( '' != $brand ) {
						$item[ $this->prefix . 'brand' ] = $brand;
					}

					$gtin = WPSEO_Meta::get_value( 'pf-gtin', $wc_product->id );
					if ( '' != $gtin ) {
						$item[ $this->prefix . 'gtin' ] = $gtin;
					}

					$mpn = WPSEO_Meta::get_value( 'pf-mpn', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'mpn' ] = $mpn;
					}

				}

				// Add the identifier_exists tag
				$item[ $this->prefix . 'identifier_exists' ] = $identifier_exists;

				// Apparel Only
				if ( 'Apparel & Accessories' == $categories_meta[0] ) {

					// Gender
					$gender = WPSEO_Meta::get_value( 'pf-gender', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'gender' ] = $gender;
					}

					// Age Group
					$age_group = WPSEO_Meta::get_value( 'pf-age_group', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'age_group' ] = $age_group;
					}

					// Color
					$color = WPSEO_Meta::get_value( 'pf-color', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'color' ] = str_replace( "/", "&#47;", $color );
					}

					// Size
					$size = WPSEO_Meta::get_value( 'pf-size', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'size' ] = $size;
					}

					// Size Type
					$size_type = WPSEO_Meta::get_value( 'pf-size_type', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'size_type' ] = $size_type;
					}

					// Size System
					$size_system = WPSEO_Meta::get_value( 'pf-size_system', $wc_product->id );
					if ( '' != $mpn ) {
						$item[ $this->prefix . 'size_system' ] = $size_system;
					}

					// Add the shipping weight
					if ( '' != $wc_product->get_weight() ) {
						$item[ $this->prefix . 'shipping_weight' ] = $wc_product->get_weight() . ' ' . get_option( 'woocommerce_weight_unit' );
					}

					// Adult
					$adult = WPSEO_Meta::get_value( 'pf-adult', $wc_product->id );
					if ( '' != $adult ) {
						$item[ $this->prefix . 'adult' ] = $adult;
					}

					// multipack
					$multipack = WPSEO_Meta::get_value( 'pf-multipack', $wc_product->id );
					if ( '' != $multipack ) {
						$item[ $this->prefix . 'multipack' ] = $multipack;
					}

					// is_bundle
					$is_bundle = WPSEO_Meta::get_value( 'pf-is_bundle', $wc_product->id );
					if ( '' != $is_bundle ) {
						$item[ $this->prefix . 'is_bundle' ] = $is_bundle;
					}

					// adwords_grouping
					$adwords_grouping = WPSEO_Meta::get_value( 'pf-adwords_grouping', $wc_product->id );
					if ( '' != $adwords_grouping ) {
						$item[ $this->prefix . 'adwords_grouping' ] = $adwords_grouping;
					}

					// adwords_labels
					$adwords_labels = WPSEO_Meta::get_value( 'pf-adwords_labels', $wc_product->id );
					if ( '' != $adwords_labels ) {
						$adwords_labels_arr = explode( ',', $adwords_labels );
						if ( count( $adwords_labels_arr ) && count( $adwords_labels_arr ) > 0 ) {
							foreach ( $adwords_labels_arr as $adwords_label ) {
								$item[ $this->prefix . 'adwords_label' ][] = trim( $adwords_label );
							}
						}
					}

					// adwords_redirect
					$adwords_redirect = WPSEO_Meta::get_value( 'pf-adwords_redirect', $wc_product->id );
					if ( '' != $adwords_redirect ) {
						$item[ $this->prefix . 'adwords_redirect' ] = $adwords_redirect;
					}

					// energy_efficiency_class
					$energy_efficiency_class = WPSEO_Meta::get_value( 'pf-energy_efficiency_class', $wc_product->id );
					if ( '' != $energy_efficiency_class && 'none' != $energy_efficiency_class ) {
						$item[ $this->prefix . 'energy_efficiency_class' ] = $energy_efficiency_class;
					}

				}

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
					if ( is_array( $value ) ) {
						foreach ( $value as $sub_value ) {
							echo '<' . $tag . '>' . $sub_value . '</' . $tag . '>' . PHP_EOL;
						}
					} else {
						echo '<' . $tag . '>' . $value . '</' . $tag . '>' . PHP_EOL;
					}

				}

				echo '</item>' . PHP_EOL;
			}
		}

		echo '</channel>' . PHP_EOL;
		echo '</rss>' . PHP_EOL;

	}

}