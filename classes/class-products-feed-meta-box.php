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


		$mbs['pf-condition'] = array(
			"name"        => "products-feed-condition",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'new'         => __( 'New', 'yoast-woo-seo' ),
				'used'        => __( 'Used', 'yoast-woo-seo' ),
				'refurbished' => __( 'Refurbished', 'yoast-woo-seo' )
			),
			"title"       => __( 'Condition', 'yoast-woo-seo' ),
			"description" => __( 'Condition or state of the item.', 'yoast-woo-seo' ),
		);

		// Unique Product Identifiers

		$mbs['pf-brand'] = array(
			"name"        => "products-feed-brand",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Brand', 'yoast-woo-seo' ),
			"description" => __( 'You must not provide your store name as the brand unless you manufacture the product.', 'yoast-woo-seo' ),
		);

		$mbs['pf-gtin'] = array(
			"name"        => "products-feed-gtin",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'GTIN', 'yoast-woo-seo' ),
			"description" => __( "Use the 'gtin' attribute to submit Global Trade Item Numbers (GTINs).", 'yoast-woo-seo' ),
		);

		$mbs['pf-mpn'] = array(
			"name"        => "products-feed-mpn",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'MPN', 'yoast-woo-seo' ),
			"description" => __( 'A Manufacturer Part Number is used to reference and identify a product using a manufacturer specific naming other than GTIN.', 'yoast-woo-seo' ),
		);

		$mbs['pf-identifier_exists'] = array(
			"name"        => "products-feed-identifier_exists",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'true'  => __( 'TRUE', 'yoast-woo-seo' ),
				'false' => __( 'FALSE', 'yoast-woo-seo' )
			),
			"title"       => __( 'Identifier Exists', 'yoast-woo-seo' ),
			"description" => __( "Some products don't have an unique identifier like gtin/mpn, if this is one of these products leave the above identifier fields blank and set this to FALSE.", 'yoast-woo-seo' ),
		);

		// Apparel Products
		$mbs['pf-gender'] = array(
			"name"        => "products-feed-gender",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'male'   => __( 'Male', 'yoast-woo-seo' ),
				'female' => __( 'Female', 'yoast-woo-seo' ),
				'unisex' => __( 'Unisex', 'yoast-woo-seo' )
			),
			"title"       => __( 'Gender', 'yoast-woo-seo' ),
			"description" => __( "Select the gender this product targets.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-age_group'] = array(
			"name"        => "products-feed-age_group",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'newborn' => __( 'Newborn', 'yoast-woo-seo' ),
				'infant'  => __( 'Infant', 'yoast-woo-seo' ),
				'toddler' => __( 'Toddler', 'yoast-woo-seo' ),
				'kids'    => __( 'Kids', 'yoast-woo-seo' ),
				'adult'   => __( 'Adult', 'yoast-woo-seo' )
			),
			"title"       => __( 'Age Group', 'yoast-woo-seo' ),
			"description" => __( "Use this to indicate the demographic of your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-color'] = array(
			"name"        => "products-feed-color",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Color', 'yoast-woo-seo' ),
			"description" => __( "This defines the dominant color(s) for an item, state colors as text (e.g. 'Black'). When a single item has multiple colors, you can submit up to two additional values as accent colors. Combine the colors with ‘/’ in order of prominence, limit the number of colors submitted to three values.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size'] = array(
			"name"        => "products-feed-size",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Size', 'yoast-woo-seo' ),
			"description" => __( "This indicates the size of a product. You may provide any values which are appropriate to your items. You can also submit the ‘size type’ and ‘size system’ attributes to provide more details about your sizing.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size_type'] = array(
			"name"        => "products-feed-size_type",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'regular'      => __( 'Regular', 'yoast-woo-seo' ),
				'petite'       => __( 'Petite', 'yoast-woo-seo' ),
				'plus'         => __( 'Plus', 'yoast-woo-seo' ),
				'big and tall' => __( 'Big and Tall', 'yoast-woo-seo' ),
				'maternity'    => __( 'Maternity', 'yoast-woo-seo' )
			),
			"title"       => __( 'Size Type', 'yoast-woo-seo' ),
			"description" => __( "Use this attribute to indicate the cut of your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size_system'] = array(
			"name"        => "products-feed-size_system",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'US'         => __( 'US', 'yoast-woo-seo' ),
				'UK'         => __( 'UK', 'yoast-woo-seo' ),
				'EU'         => __( 'EU', 'yoast-woo-seo' ),
				'DE'         => __( 'DE', 'yoast-woo-seo' ),
				'FR'         => __( 'FR', 'yoast-woo-seo' ),
				'JP'         => __( 'JP', 'yoast-woo-seo' ),
				'CN (China)' => __( 'CN (China)', 'yoast-woo-seo' ),
				'IT'         => __( 'IT', 'yoast-woo-seo' ),
				'BR'         => __( 'BR', 'yoast-woo-seo' ),
				'MEX'        => __( 'MEX', 'yoast-woo-seo' ),
				'AU'         => __( 'AU', 'yoast-woo-seo' ),
			),
			"title"       => __( 'Size System', 'yoast-woo-seo' ),
			"description" => __( "Use this attribute to indicate the country’s sizing system in which you are submitting your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);


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
		$content .= '<tr><th>Product category:</th><td>';

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
		$content .= '</td></tr>';

		// Add the metabox fields
		foreach ( $this->get_meta_boxes() as $meta_key => $meta_box ) {
			$content .= $this->do_meta_box( $meta_box, $meta_key );
		}

		$this->do_tab( 'products_feed', __( 'Products Feed', 'wordpress-seo-news' ), $content );
	}


}