<?php

class WPSEO_Woo_Products_Feed_Meta_Box extends WPSEO_Metabox {

	//private $options;

	public function __construct() {
		//$this->options = WPSEO_News::get_options();
	}

	/**
	 * The metaboxes to display and save for the tab
	 *
	 * @return array $mbs
	 */
	public function get_meta_boxes() {
		$mbs = array();

		/*
				$mbs['products-feed-category[]'] = array(
					"name"        => "category",
					"std"         => "",
					"type"        => "select",
					"options"     => $input_categories,
					"title"       => __( "Product category", 'yoast-woo-seo' ),
					"class"       => "wpseo_woo_pf_categories"
				);
		*/


		return $mbs;
	}

	/**
	 * Add the meta boxes to meta box array so they get saved
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function save( $meta_boxes ) {
		$meta_boxes = array_merge( $meta_boxes, $this->get_meta_boxes() );

		return $meta_boxes;
	}

	/**
	 * Save the products feed category
	 */
	public function save_category() {

		// Save the category
		if ( isset( $_POST['post_ID'] ) && isset( $_POST['yoast_wpseo_products-feed-category'] ) ) {
			update_post_meta( $_POST['post_ID'], 'yoast_wpseo_products-feed-category', $_POST['yoast_wpseo_products-feed-category'] );
		}
	}

	/**
	 * Add WordPress SEO meta fields to WPSEO meta class
	 *
	 * @param $meta_fields
	 *
	 * @return mixed
	 */
	public function add_meta_fields_to_wpseo_meta( $meta_fields ) {

		$meta_fields['products_feed'] = $this->get_meta_boxes();

		return $meta_fields;
	}

	/**
	 * The tab header
	 */
	public function header() {
		global $post;

		if ( 'product' == $post->post_type ) {
			echo '<li class="news"><a class="wpseo_tablink" href="#wpseo_products_feed">' . __( 'Products Feed', 'wordpress-seo-news' ) . '</a></li>';
		}

	}

	/**
	 * The tab content
	 */
	public function content() {
		global $post;

		if ( 'product' != $post->post_type ) {
			return;
		}

		// Build tab content
		$content = '';

		// Products Feed Manager
		$products_feed_manager = new WPSEO_Woo_Products_Feed_Manager();

		// Get category from post meta
		$categories_meta = get_post_meta( $post->ID, 'yoast_wpseo_products-feed-category', true );

		// Add the Product category table
		$content .= '<table class="form-table"><tbody><tr><th>Product category:</th><td>';

		// Loop through meta categories
		if ( is_array( $categories_meta ) && count( $categories_meta ) > 0 ) {
			for ( $i = 0; $i < count( $categories_meta ); $i ++ ) {

				$parents = null;
				if ( $i > 0 ) {
					$parents = array();
					for ( $j = 0; $j < $i; $j ++ ) {
						$parents[] = $categories_meta[ $j ];
					}
				}

				// Custom category fields
				$categories = $products_feed_manager->get_categories( $parents );

				// Format array for input select field
				$input_categories = array();
				if ( count( $categories ) > 0 ) {
					foreach ( $categories as $category ) {
						$input_categories[ $category ] = $category;
					}
				}


				$content .= '<select name="yoast_wpseo_products-feed-category[]" id="yoast_wpseo_products-feed-category[]" class="yoast wpseo_woo_pf_categories">' . PHP_EOL;
				foreach ( $input_categories as $input_category ) {
					$content .= '<option value="' . $input_category . '"' . ( ( $categories_meta[ $i ] == $input_category ) ? 'selected="selected"' : '' ) . '>' . $input_category . '</option>' . PHP_EOL;
				}
				$content .= '</select>' . PHP_EOL;

			}
		}

		// Category table closer
		$content .= '</td></tr></tbody></table>';

		// Add the metabox fields
		foreach ( $this->get_meta_boxes() as $meta_key => $meta_box ) {
			$content .= $this->do_meta_box( $meta_box, $meta_key );
		}

		$this->do_tab( 'products_feed', __( 'Products Feed', 'wordpress-seo-news' ), $content );
	}


}